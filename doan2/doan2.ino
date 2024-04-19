#include <dummy.h>
#include <WiFi.h>
#include <WebServer.h>
#include <HTTPClient.h>

#include <SPI.h>
#include <MFRC522.h>
#include <LiquidCrystal_I2C.h>

LiquidCrystal_I2C lcd(0x27,16,2); 
// static const uint8_t SDA = 21;
// static const uint8_t SCL = 22;   
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//

#define SS_PIN 4    // Ví dụ: SDA/SS connected to GPIO 5
#define RST_PIN 0   // Ví dụ: RST connected to GPIO 0
#define ON_Board_LED 2 // Ví dụ: On-board LED connected to GPIO 2
#define buzer 25  

MFRC522 mfrc522(SS_PIN, RST_PIN); 

const char* ssid = "WAN";
const char* password = "12345678";

WebServer server(80);  // Server on port 80

int readsuccess;
byte readcard[4];
char str[32] = "";
String StrUID;
String UIDresultSend, postData;
unsigned long startTime = millis();
unsigned long waitTime = 5000;  // 5 giây
void setup() {
  Serial.begin(115200); //--> Initialize serial communications with the PC
  SPI.begin();      //--> Init SPI bus
  mfrc522.PCD_Init(); //--> Init MFRC522 card
  delay(500);
  pinMode(buzer,OUTPUT);   
  pinMode(ON_Board_LED,OUTPUT); 
  digitalWrite(ON_Board_LED, HIGH); //--> Turn off Led On Board
  digitalWrite(buzer, HIGH); 
  lcd.init();
  lcd.backlight();
  lcd.setCursor(0,1);
  lcd.print("MOI BAN QUET");

  WiFi.begin(ssid, password);
  Serial.println("");
  Serial.print("Connecting");
  while (WiFi.status() != WL_CONNECTED) {
    Serial.print(".");
    //----------------------------------------Make the On Board Flashing LED on the process of connecting to the wifi router.
    digitalWrite(ON_Board_LED, LOW);
    delay(250);
    digitalWrite(ON_Board_LED, HIGH);
    delay(250);
  }
  digitalWrite(ON_Board_LED, HIGH); //--> Turn off the On Board LED when it is connected to the wifi router.
  //----------------------------------------If successfully connected to the wifi router, the IP Address that will be visited is displayed in the serial monitor
  Serial.println("");
  Serial.print("Successfully connected to : ");
  Serial.println(ssid);
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP());

  Serial.println("Please tag a card or keychain to see the UID !");
  Serial.println("");

}
void loop() {

  readsuccess = getid();
  digitalWrite(buzer, HIGH); 
  if(readsuccess) 
  {
    
    digitalWrite(buzer, LOW);
    delay(400);
    digitalWrite(buzer, HIGH); 

    digitalWrite(ON_Board_LED, LOW);
    HTTPClient http;    //Declare object of class HTTPClient
    UIDresultSend = StrUID;
   
    //Post Data
    postData = "UIDresult=" + UIDresultSend;
  
    http.begin("http://192.168.131.78:80/NodeMCU_RS522_Mysql/getUID.php");  //Specify request destination
    http.addHeader("Content-Type", "application/x-www-form-urlencoded"); //Specify content-type header
   
    int httpCode = http.POST(postData);   //Send the request
    String payload = http.getString();    //Get the response payload

    Serial.println(UIDresultSend);
    Serial.println(httpCode);   //Print HTTP return code
    Serial.print("pay load: ");
    Serial.println(payload);    //Print request response payload
    
    http.end();  //Close connection
    delay(1000);
    digitalWrite(ON_Board_LED, HIGH);

  }
  if (readsuccess) 
  {
  lcd.setCursor(0, 0); // Hiển thị mặc định trên LCD
  lcd.print("UID: ");
  lcd.print(UIDresultSend);
  lcd.setCursor(0,1);
  lcd.print("QUET THANH CONG");
  startTime = millis();
  }
  else 
  {
      if(millis() - startTime > 10000)
      {
          lcd.setCursor(0, 0); // Hiển thị mặc định trên LCD
          lcd.print("               ");
          lcd.setCursor(0,1);
          lcd.print("MOI BAN QUET   ");
      }

  }
    // Serial.print("readsuccess: ");
    // Serial.println(readsuccess);
    // Serial.print("start: ");
    // Serial.println(startTime);
    // Serial.print("millis : ");
    // Serial.println(millis());
}

int getid()
 {  
  if(!mfrc522.PICC_IsNewCardPresent()) {
    return 0;
  }
  if(!mfrc522.PICC_ReadCardSerial()) {
    return 0;
  }
 
  
  Serial.print("THE UID OF THE SCANNED CARD IS : ");
  
  for(int i=0;i<4;i++){
    readcard[i]=mfrc522.uid.uidByte[i]; //storing the UID of the tag in readcard
    array_to_string(readcard, 4, str);
    StrUID = str;
  }
  mfrc522.PICC_HaltA();
  return 1;
}

void array_to_string(byte array[], unsigned int len, char buffer[]) {
    for (unsigned int i = 0; i < len; i++)
    {
        byte nib1 = (array[i] >> 4) & 0x0F;
        byte nib2 = (array[i] >> 0) & 0x0F;
        buffer[i*2+0] = nib1  < 0xA ? '0' + nib1  : 'A' + nib1  - 0xA;
        buffer[i*2+1] = nib2  < 0xA ? '0' + nib2  : 'A' + nib2  - 0xA;
    }
    buffer[len*2] = '\0';
}
