#include <WiFi.h>
#include <HTTPClient.h>
#include <Wire.h>
#include <LiquidCrystal_I2C.h>

#define TRIG 25
#define ECHO 33

#define LCD_ADDRESS 0x27
#define LCD_COLS 16
#define LCD_ROWS 2

LiquidCrystal_I2C lcd(LCD_ADDRESS, LCD_COLS, LCD_ROWS);

const char* ssid = "Cesar Batista";
const char* password = "2505300427";

// TOKEN da placa cadastrado no banco
const char* deviceToken = "TOKEN123";

// URL correta do seu receber.php
const char* serverName = "http://192.168.100.72/PI-4-Semestre/EcoCiclo/controllers/receber.php";

// Calibração da lixeira
float distanciaVazia = 40.0; // cm
float distanciaCheia = 5.0;  // cm

// Intervalos
const unsigned long SENSOR_INTERVAL_MS = 500;
const unsigned long LCD_INTERVAL_MS = 2000;
const unsigned long SERVER_INTERVAL_MS = 10000; // 10 segundos
const unsigned long WIFI_RETRY_INTERVAL_MS = 10000;

// Controle de tempo
unsigned long lastSensorRead = 0;
unsigned long lastLcdUpdate = 0;
unsigned long lastServerSend = 0;
unsigned long lastWifiRetry = 0;

// Estado atual
float distanciaAtual = -1.0;
int porcentagemAtual = -1;
bool sinalValidoAtual = false;

long medirDistancia() {
  digitalWrite(TRIG, LOW);
  delayMicroseconds(2);

  digitalWrite(TRIG, HIGH);
  delayMicroseconds(10);
  digitalWrite(TRIG, LOW);

  long duracao = pulseIn(ECHO, HIGH, 30000);

  if (duracao == 0) {
    return -1;
  }

  long distancia = duracao * 0.034 / 2.0;
  return distancia;
}

long mediaDistancia(int amostras) {
  long soma = 0;
  int validas = 0;

  for (int i = 0; i < amostras; i++) {
    long d = medirDistancia();
    if (d > 0) {
      soma += d;
      validas++;
    }
    delay(30);
  }

  if (validas == 0) {
    return -1;
  }

  return soma / validas;
}

int calcularPorcentagem(float distancia) {
  if (distancia < 0) {
    return -1;
  }

  float porcentagem = ((distanciaVazia - distancia) / (distanciaVazia - distanciaCheia)) * 100.0;

  if (porcentagem < 0) porcentagem = 0;
  if (porcentagem > 100) porcentagem = 100;

  return (int)round(porcentagem);
}

void conectarWiFi() {
  if (WiFi.status() == WL_CONNECTED) {
    return;
  }

  Serial.print("Conectando ao Wi-Fi");
  WiFi.begin(ssid, password);

  unsigned long inicio = millis();
  while (WiFi.status() != WL_CONNECTED && millis() - inicio < 10000) {
    delay(500);
    Serial.print(".");
  }

  Serial.println();

  if (WiFi.status() == WL_CONNECTED) {
    Serial.println("Wi-Fi conectado!");
    Serial.print("IP da ESP32: ");
    Serial.println(WiFi.localIP());
  } else {
    Serial.println("Falha ao conectar no Wi-Fi.");
  }
}

void manterWiFi() {
  if (WiFi.status() == WL_CONNECTED) {
    return;
  }

  unsigned long agora = millis();
  if (agora - lastWifiRetry >= WIFI_RETRY_INTERVAL_MS) {
    lastWifiRetry = agora;
    Serial.println("Tentando reconectar ao Wi-Fi...");
    WiFi.disconnect();
    WiFi.begin(ssid, password);
  }
}

void atualizarLCD() {
  lcd.clear();

  lcd.setCursor(0, 0);
  lcd.print("EcoCiclo");

  lcd.setCursor(0, 1);
  if (!sinalValidoAtual || porcentagemAtual < 0) {
    lcd.print("Sem leitura");
    return;
  }

  lcd.print("Nivel: ");
  lcd.print(porcentagemAtual);
  lcd.print("%");
}

bool enviarDadosServidor(float distancia, int porcentagem, bool sinalValido) {
  if (WiFi.status() != WL_CONNECTED) {
    Serial.println("Wi-Fi desconectado. Nao foi possivel enviar.");
    return false;
  }

  HTTPClient http;
  http.begin(serverName);
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");

  String dados = "device_token=" + String(deviceToken) +
                 "&distancia=" + String(distancia, 2) +
                 "&porcentagem=" + String(porcentagem) +
                 "&sinal_valido=" + String(sinalValido ? 1 : 0);

  int httpResponseCode = http.POST(dados);

  Serial.print("HTTP Response code: ");
  Serial.println(httpResponseCode);

  if (httpResponseCode > 0) {
    String resposta = http.getString();
    Serial.print("Resposta do servidor: ");
    Serial.println(resposta);
    http.end();
    return true;
  }

  Serial.println("Erro ao enviar dados para o servidor.");
  http.end();
  return false;
}

void setup() {
  Serial.begin(115200);

  pinMode(TRIG, OUTPUT);
  pinMode(ECHO, INPUT);

  Wire.begin(21, 22);
  lcd.init();
  lcd.backlight();
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("EcoCiclo");
  lcd.setCursor(0, 1);
  lcd.print("Inicializando");

  conectarWiFi();

  lastSensorRead = millis();
  lastLcdUpdate = millis();
  lastServerSend = millis();
}

void loop() {
  unsigned long agora = millis();

  manterWiFi();

  if (agora - lastSensorRead >= SENSOR_INTERVAL_MS) {
    lastSensorRead = agora;

    long distanciaLida = mediaDistancia(3);

    if (distanciaLida > 0) {
      distanciaAtual = (float)distanciaLida;
      porcentagemAtual = calcularPorcentagem(distanciaAtual);
      sinalValidoAtual = true;

      Serial.print("Distancia: ");
      Serial.print(distanciaAtual);
      Serial.print(" cm | Nivel: ");
      Serial.print(porcentagemAtual);
      Serial.println("%");
    } else {
      sinalValidoAtual = false;
      porcentagemAtual = -1;
      distanciaAtual = -1;
      Serial.println("Sem eco / fora de alcance");
    }
  }

  if (agora - lastLcdUpdate >= LCD_INTERVAL_MS) {
    lastLcdUpdate = agora;
    atualizarLCD();
  }

  if (agora - lastServerSend >= SERVER_INTERVAL_MS) {
    lastServerSend = agora;

    if (sinalValidoAtual && porcentagemAtual >= 0) {
      enviarDadosServidor(distanciaAtual, porcentagemAtual, sinalValidoAtual);
    } else {
      Serial.println("Leitura invalida. Envio ignorado.");
    }
  }
}