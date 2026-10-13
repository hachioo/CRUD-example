<?php
/* ══════════════════════════════════════════
   POST DATABASE
   Add more posts here following the same schema.
══════════════════════════════════════════ */
$POSTS = [
  'esp32-guide' => [
    'title'        => 'Getting Started with ESP32: Comprehensive Guide from GPIO to OTA Update',
    'cat'          => 'Tutorials',
    'catColor'     => '#c8410f',
    'heroImg'      => 'img/esp32 getting started.png',
    'author'       => 'VoltCore Team',
    'avatar'       => '',
    'date'         => 'April 1, 2025',
    'time'         => '8',
    'views'        => '34,200',
    'heroEmoji'    => '',
    'heroGradient' => 'linear-gradient(135deg,#1a0d08,#2d1506)',
    'tags'         => ['ESP32', 'Arduino', 'IoT', 'WiFi', 'OTA'],
    'toc'          => [
      'Why ESP32?',
      'Setting Up the IDE',
      'GPIO Basics',
      'WiFi & BLE',
      'OTA Firmware Updates',
    ],
    'content'      => <<<'HTML'
      <h2 id="s0">Why ESP32?</h2>
      <p>The ESP32 is a game-changer for maker projects. At under $5, you get a dual-core Xtensa LX6 processor running at up to 240 MHz, 520 KB SRAM, integrated 802.11 b/g/n WiFi, Bluetooth 4.2/5.0, and 34 programmable GPIO pins — all on a single chip.</p>
      <div class="info-box">
        <svg class="icon" viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="#2563a8" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 11v5"/><path d="M12 8h.01"/></svg>
        <p>Compared to the Arduino Uno, the ESP32 is roughly <strong>10× faster</strong>, has <strong>100× more RAM</strong>, and adds wireless connectivity. For IoT projects, it's the obvious choice.</p>
      </div>

      <h2 id="s1">Setting Up the IDE</h2>
      <p>You can use the Arduino IDE (recommended for beginners) or PlatformIO in VS Code (recommended for professional projects). We'll use Arduino IDE 2.x here.</p>
      <ol>
        <li>Download Arduino IDE 2.x from <code>arduino.cc/en/software</code></li>
        <li>Open <strong>File → Preferences</strong> and paste the ESP32 board URL into "Additional Board Manager URLs":</li>
      </ol>
      <pre><code>https://raw.githubusercontent.com/espressif/arduino-esp32/gh-pages/package_esp32_index.json</code></pre>
      <p>Then go to <strong>Tools → Board → Board Manager</strong>, search for "esp32" by Espressif, and install version 2.x or later.</p>

      <h2 id="s2">GPIO Basics</h2>
      <p>GPIO (General Purpose Input/Output) pins let you read sensors, control LEDs, drive motors, and communicate with other devices. The ESP32-DevKitC exposes 34 GPIO pins, though some have restrictions.</p>
      <h3>Blink an LED</h3>
      <pre><code>#define LED_PIN 2   // Built-in LED on most boards

void setup() {
  pinMode(LED_PIN, OUTPUT);
}

void loop() {
  digitalWrite(LED_PIN, HIGH);  // ON
  delay(500);
  digitalWrite(LED_PIN, LOW);   // OFF
  delay(500);
}</code></pre>
      <blockquote>
        <p>GPIO 34–39 are <em>input-only</em> — they have no internal pull-up/pull-down resistors. Avoid using them for output.</p>
      </blockquote>

      <h2 id="s3">WiFi & BLE</h2>
      <p>Connecting the ESP32 to WiFi takes less than 10 lines of code thanks to the <code>WiFi.h</code> library bundled with the Espressif Arduino core.</p>
      <pre><code>#include &lt;WiFi.h&gt;

const char* ssid     = "YourSSID";
const char* password = "YourPassword";

void setup() {
  Serial.begin(115200);
  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nConnected! IP: " + WiFi.localIP().toString());
}

void loop() { }</code></pre>
      <p>For Bluetooth Low Energy (BLE), use the <code>BLEDevice</code> library included in the core. BLE is ideal for low-power peripherals like heart rate monitors and beacons.</p>

      <h2 id="s4">OTA Firmware Updates</h2>
      <p>Over-the-Air (OTA) updates let you push new firmware to a deployed device over WiFi — no USB cable required. This is critical for production IoT products.</p>
      <pre><code>#include &lt;ArduinoOTA.h&gt;
#include &lt;WiFi.h&gt;

void setup() {
  WiFi.begin("SSID", "PASSWORD");
  while (WiFi.status() != WL_CONNECTED) delay(500);

  ArduinoOTA.setHostname("voltcore-device");
  ArduinoOTA.setPassword("ota-secret");
  ArduinoOTA.begin();
}

void loop() {
  ArduinoOTA.handle();  // Must call every loop
  // Your main code here
}</code></pre>
      <div class="info-box">
        <svg class="icon" viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="#2563a8" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 11v5"/><path d="M12 8h.01"/></svg>
        <p>Always set a strong OTA password. An unprotected OTA endpoint is a serious security vulnerability in production devices.</p>
      </div>
      <p>You can now upload firmware from <strong>Tools → Port → Network Ports</strong> in Arduino IDE, selecting your device's hostname on the local network.</p>
HTML
  ],

  'i2c-spi-uart' => [
    'title'        => 'I2C vs SPI vs UART: When to Use Which Protocol?',
    'cat'          => 'Technical',
    'catColor'     => '#2563a8',
    'author'       => 'Sigma Boy',
    'avatar'       => '',
    'date'         => 'March 22, 2025',
    'time'         => '6',
    'views'        => '14,200',
    'heroImg'      => 'img/uartvsi2cvsspi.png',
    'heroEmoji'    => '',
    'heroGradient' => 'linear-gradient(135deg,#071a10,#0d2a1a)',
    'tags'         => ['I2C', 'SPI', 'UART', 'Protocols', 'Embedded'],
    'toc'          => ['UART', 'I2C', 'SPI', 'Quick Comparison', 'Which to Choose?'],
    'content'      => <<<'HTML'
      <h2 id="s0">UART — Universal Asynchronous Receiver/Transmitter</h2>
      <p>UART is the simplest of the three. It uses just two wires (TX and RX), requires no shared clock, and is perfect for point-to-point communication — think serial monitor, GPS modules, or GSM modules.</p>
      <ul>
        <li><strong>Wires:</strong> 2 (TX, RX)</li>
        <li><strong>Devices:</strong> Point-to-point only</li>
        <li><strong>Speed:</strong> Up to ~5 Mbps in theory, 115200 bps typical</li>
        <li><strong>Use when:</strong> Talking to a GPS, Bluetooth module, or PC terminal</li>
      </ul>

      <h2 id="s1">I2C — Inter-Integrated Circuit</h2>
      <p>I2C uses two wires (SDA and SCL) and supports multiple devices on the same bus, each identified by a 7-bit address. Ideal for sensors and low-speed peripherals.</p>
      <pre><code>#include &lt;Wire.h&gt;

void setup() {
  Wire.begin();                  // Join bus as master
  Wire.beginTransmission(0x68);  // Start talking to device at 0x68
  Wire.write(0x6B);              // Register address (e.g. MPU-6050 power)
  Wire.write(0);                 // Value to write
  Wire.endTransmission();
}</code></pre>
      <div class="info-box">
        <svg class="icon" viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="#2563a8" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 11v5"/><path d="M12 8h.01"/></svg>
        <p>I2C requires pull-up resistors on both SDA and SCL lines — typically 4.7 kΩ to 10 kΩ. Most breakout boards include these.</p>
      </div>

      <h2 id="s2">SPI — Serial Peripheral Interface</h2>
      <p>SPI is the fastest of the three. It uses 4 wires (MOSI, MISO, SCK, CS) and achieves speeds up to 80 MHz on ESP32. Each device needs its own CS (Chip Select) pin.</p>
      <ul>
        <li><strong>Wires:</strong> 4+ (MOSI, MISO, SCK, CS per device)</li>
        <li><strong>Devices:</strong> Multiple (one CS pin each)</li>
        <li><strong>Speed:</strong> Up to 80 MHz on ESP32</li>
        <li><strong>Use when:</strong> Driving TFT displays, SD cards, high-speed ADCs</li>
      </ul>

      <h2 id="s3">Quick Comparison</h2>
      <p>Here's how the three protocols stack up at a glance:</p>
      <ul>
        <li><strong>UART</strong> — Simple, 2 wires, point-to-point, moderate speed</li>
        <li><strong>I2C</strong> — 2 wires, multi-device, addresses, slower but very convenient</li>
        <li><strong>SPI</strong> — 4+ wires, multi-device, fastest, best for data-heavy peripherals</li>
      </ul>

      <h2 id="s4">Which to Choose?</h2>
      <blockquote><p>Use <strong>I2C</strong> when wiring space matters and speed is under 400 kHz. Use <strong>SPI</strong> for displays, SD cards, and anything needing high throughput. Use <strong>UART</strong> for simple one-to-one communication.</p></blockquote>
      <p>In most real designs, you'll use all three — I2C for sensors, SPI for the display, and UART for debugging and external modules.</p>
HTML
  ],

  'tflite-esp32' => [
    'title'        => 'Running TensorFlow Lite on ESP32: Offline Voice Recognition',
    'cat'          => 'AI & IoT',
    'catColor'     => '#0891b2',
    'author'       => 'Edge Impulse',
    'avatar'       => '',
    'date'         => 'March 15, 2025',
    'time'         => '12',
    'views'        => '21,000',
    'heroImg'      => 'img/speechrec.png',
    'heroEmoji'    => '',
    'heroGradient' => 'linear-gradient(135deg,#100a1a,#1c0d2e)',
    'tags'         => ['TensorFlow Lite', 'ESP32', 'AI', 'Voice', 'ML'],
    'toc'          => ['Why TFLite on MCUs?', 'Model Preparation', 'Audio Pipeline', 'Inference Loop', 'Results'],
    'content'      => <<<'HTML'
      <h2 id="s0">Why TFLite on Microcontrollers?</h2>
      <p>Running inference directly on an ESP32 means zero latency, no internet dependency, and sub-milliwatt idle power. For wake-word detection ("Hey VoltCore!"), this is exactly what you need.</p>
      <div class="info-box">
        <svg class="icon" viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="#2563a8" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 11v5"/><path d="M12 8h.01"/></svg>
        <p>TensorFlow Lite for Microcontrollers (TFLite Micro) targets devices with as little as 256 KB RAM. The ESP32 has 520 KB — plenty for a compact keyword-detection model.</p>
      </div>

      <h2 id="s1">Model Preparation</h2>
      <p>Train a keyword recognition model using Google's Speech Commands dataset, then quantize it to 8-bit integers to shrink it from ~1 MB to ~40 KB:</p>
      <pre><code># Convert and quantize with TFLite converter
converter = tf.lite.TFLiteConverter.from_saved_model(saved_model_dir)
converter.optimizations = [tf.lite.Optimize.DEFAULT]
converter.target_spec.supported_ops = [tf.lite.OpsSet.TFLITE_BUILTINS_INT8]
tflite_model = converter.convert()

# Save the binary
with open('model.tflite', 'wb') as f:
    f.write(tflite_model)</code></pre>
      <p>Then convert to a C array for embedding in your firmware:</p>
      <pre><code>xxd -i model.tflite > model_data.cc</code></pre>

      <h2 id="s2">Audio Pipeline</h2>
      <p>The I2S digital microphone (like INMP441) streams 16-bit PCM audio at 16 kHz directly to the ESP32's I2S peripheral, bypassing the CPU until a full 1-second window is ready.</p>
      <pre><code>#include &lt;driver/i2s.h&gt;

i2s_config_t i2s_config = {
  .mode = (i2s_mode_t)(I2S_MODE_MASTER | I2S_MODE_RX),
  .sample_rate = 16000,
  .bits_per_sample = I2S_BITS_PER_SAMPLE_16BIT,
  .channel_format = I2S_CHANNEL_FMT_ONLY_LEFT,
  .communication_format = I2S_COMM_FORMAT_STAND_I2S,
  .intr_alloc_flags = 0,
  .dma_buf_count = 8,
  .dma_buf_len = 512,
};</code></pre>

      <h2 id="s3">Inference Loop</h2>
      <p>On each 1-second audio window: compute a Mel spectrogram, flatten to a 1960-element float array, quantize to int8, and run the TFLite interpreter.</p>
      <pre><code>// Quantize input
for (int i = 0; i &lt; kInputSize; i++) {
  input->data.int8[i] = (int8_t)(mel_features[i] / scale + zero_point);
}

// Run inference
TfLiteStatus invoke_status = interpreter->Invoke();

// Read output
int8_t* output = output_tensor->data.int8;
int prediction = std::distance(output, std::max_element(output, output + kLabelCount));</code></pre>

      <h2 id="s4">Results</h2>
      <p>On our test set of 500 utterances per keyword, the quantized model achieved 94.2% accuracy with an inference time of just <strong>18 ms</strong> at 240 MHz. Current draw during inference peaked at 120 mA, dropping to 8 µA in deep sleep between windows.</p>
      <blockquote><p>With a 3000 mAh LiPo and wake-word polling every 1 second, estimated battery life exceeds 14 months.</p></blockquote>
HTML
  ],

  'iot-power-tips' => [
    'title'        => 'Optimizing Power Consumption for IoT: Extend Battery Life 10x',
    'cat'          => 'Tips & Tricks',
    'catColor'     => '#7c3aed',
    'author'       => 'Kukukaka',
    'avatar'       => '',
    'date'         => 'March 8, 2025',
    'time'         => '7',
    'views'        => '7,600',
    'heroEmoji'    => '',
    'heroGradient' => 'linear-gradient(135deg,#071520,#0a2030)',
    'heroImg'      => 'img/optimize.png',
    'tags'         => ['IoT', 'Power', 'Battery', 'ESP32', 'DeepSleep'],
    'toc'          => ['Deep Sleep', 'Light Sleep', 'WiFi Tricks', 'CPU Scaling', 'Checklist'],
    'content'      => <<<'HTML'
      <h2 id="s0">Deep Sleep</h2>
      <p>The biggest win. ESP32 in deep sleep draws only <strong>5–10 µA</strong>, versus ~160 mA active. Use <code>esp_deep_sleep_start()</code> whenever the device doesn't need to run.</p>
      <pre><code>#include &lt;esp_sleep.h&gt;

void setup() {
  // Do your work here (read sensor, send data)
  sendSensorData();

  // Sleep for 60 seconds
  esp_sleep_enable_timer_wakeup(60 * 1000000ULL);
  esp_deep_sleep_start();
  // Code after this line never executes
}</code></pre>
      <div class="info-box">
        <svg class="icon" viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="#2563a8" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 11v5"/><path d="M12 8h.01"/></svg>
        <p>Deep sleep resets the chip on wakeup — <code>setup()</code> runs again. Store state in RTC memory with <code>RTC_DATA_ATTR</code> to survive sleep cycles.</p>
      </div>

      <h2 id="s1">Light Sleep</h2>
      <p>Light sleep keeps RAM and the CPU state intact, waking in under 1 ms. Current: ~0.8 mA — 200× less than active. Perfect for applications that need fast response.</p>
      <pre><code>esp_sleep_enable_timer_wakeup(10000);  // 10 ms
esp_light_sleep_start();
// Execution continues here after wakeup</code></pre>

      <h2 id="s2">WiFi Tricks</h2>
      <p>WiFi consumes ~160–300 mA during active transmission. Strategies to minimize this:</p>
      <ul>
        <li>Enable <strong>Modem Sleep</strong>: WiFi stays associated but powers down between DTIM beacons</li>
        <li>Use <strong>static IP</strong>: Skip DHCP negotiation (saves ~200 ms and significant energy per boot)</li>
        <li>Batch sensor readings — connect once, send a batch, disconnect</li>
        <li>Use <strong>UDP</strong> instead of TCP for small payloads (no handshake overhead)</li>
      </ul>

      <h2 id="s3">CPU Frequency Scaling</h2>
      <p>Drop the CPU clock for non-time-critical work. Each halving of frequency roughly halves dynamic power.</p>
      <pre><code>#include &lt;esp_pm.h&gt;

esp_pm_config_esp32_t pm_config = {
  .max_freq_mhz = 240,
  .min_freq_mhz = 10,
  .light_sleep_enable = true
};
esp_pm_configure(&pm_config);</code></pre>

      <h2 id="s4">Power Optimization Checklist</h2>
      <ul>
        <li>✓ Use deep sleep between sensor readings</li>
        <li>✓ Enable WiFi modem sleep when idle</li>
        <li>✓ Use static IP to skip DHCP</li>
        <li>✓ Lower CPU freq when WiFi is off</li>
        <li>✓ Disable Bluetooth if unused: <code>btStop()</code></li>
        <li>✓ Store state in RTC memory</li>
        <li>✓ Choose low-power sensors (e.g. SHT40 over DHT22)</li>
      </ul>
      <blockquote><p>Combining deep sleep, static IP, and batched transmissions, we measured a 3000 mAh cell lasting <strong>18 months</strong> on a once-per-minute soil moisture sensor.</p></blockquote>
HTML
  ],

  'arduino-r3-vs-r4' => [
    'title'        => 'Arduino Uno R3 vs R4 WiFi: Which One to Buy in 2025?',
    'cat'          => 'Reviews',
    'catColor'     => '#1a6b4a',
    'author'       => 'The Electronic Guy',
    'avatar'       => '',
    'date'         => 'March 28, 2025',
    'time'         => '5',
    'views'        => '9,800',
    'heroImg'      => 'img/r3r4.png',
    'heroEmoji'    => '',
    'heroGradient' => 'linear-gradient(135deg,#0a1929,#0d2137)',
    'tags'         => ['Arduino', 'Review', 'Comparison', 'R4', 'WiFi'],
    'toc'          => ['Core Differences', 'Performance', 'WiFi Features', 'Pricing', 'Verdict'],
    'content'      => <<<'HTML'
      <h2 id="s0">Core Differences</h2>
      <p>The R3 uses the classic ATmega328P (8-bit, 16 MHz, 2 KB SRAM). The R4 WiFi moves to a Renesas RA4M1 (32-bit Arm Cortex-M4, 48 MHz, 32 KB SRAM) and adds an ESP32-S3 co-processor for wireless.</p>
      <div class="info-box">
        <svg class="icon" viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="#2563a8" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 11v5"/><path d="M12 8h.01"/></svg>
        <p>The R4 delivers <strong>3× the clock speed</strong>, <strong>16× more SRAM</strong>, and native floating-point — making it far better suited for DSP, sensor fusion, and ML tasks.</p>
      </div>

      <h2 id="s1">Performance</h2>
      <p>We benchmarked both boards running a 1000-sample FFT and computing a 32×32 matrix multiplication:</p>
      <ul>
        <li><strong>FFT 1024pt:</strong> R3 = 380 ms · R4 = 28 ms (13.6× faster)</li>
        <li><strong>Matrix multiply:</strong> R3 = 1.4 s · R4 = 90 ms (15.6× faster)</li>
      </ul>

      <h2 id="s2">WiFi Features (R4 WiFi only)</h2>
      <p>The R4 WiFi ships with an ESP32-S3 handling 2.4 GHz 802.11 b/g/n and Bluetooth 5.0. Espressif's AT-command firmware is pre-loaded, and Arduinos WiFiS3 library wraps it cleanly. The board also has a 12×8 LED matrix built in — great for quick status displays.</p>

      <h2 id="s3">Pricing</h2>
      <p>R3 hovers around $27 official MSRP (clones as low as $3). R4 WiFi retails at $27.50 official — essentially the same price as R3 official, with massively more capability. Clones do not yet exist for R4.</p>

      <h2 id="s4">Verdict</h2>
      <blockquote><p>If you're starting fresh in 2025: buy the R4 WiFi. If you have existing R3 shields and code that must work unchanged: the R3 remains fully supported. For everything new, R4 is the clear winner.</p></blockquote>
HTML
  ],

  'pwm-guide' => [
    'title'        => 'What is PWM? Controlling LED Brightness, Motor Speed, and Servos',
    'cat'          => 'Tutorials',
    'catColor'     => '#c8410f',
    'author'       => 'Yes King',
    'avatar'       => '',
    'date'         => 'March 2, 2025',
    'time'         => '6',
    'views'        => '11,400',
    'heroImg'      => 'img/controlling led.png',
    'heroEmoji'    => '',
    'heroGradient' => 'linear-gradient(135deg,#1a0808,#2d0f0f)',
    'tags'         => ['PWM', 'Arduino', 'Motors', 'Servos', 'LED'],
    'toc'          => ['What is PWM?', 'LED Brightness', 'DC Motor Speed', 'Servo Control'],
    'content'      => <<<'HTML'
      <h2 id="s0">What is PWM?</h2>
      <p>Pulse Width Modulation (PWM) is a technique for simulating an analog output using a digital signal. By rapidly switching a pin between HIGH and LOW, and varying the ratio of HIGH time to total period (the "duty cycle"), you can control the average voltage seen by a load.</p>
      <div class="info-box">
        <svg class="icon" viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="#2563a8" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 11v5"/><path d="M12 8h.01"/></svg>
        <p>50% duty cycle = half average voltage. 100% duty cycle = full voltage. 0% duty cycle = 0 V.</p>
      </div>

      <h2 id="s1">LED Brightness</h2>
      <p>Arduino's <code>analogWrite()</code> produces 490 Hz PWM on most pins. The human eye integrates flicker above ~50 Hz, perceiving intermediate brightness.</p>
      <pre><code>int brightness = 0;
int fadeAmount = 5;

void loop() {
  analogWrite(9, brightness);   // PWM on pin 9
  brightness += fadeAmount;
  if (brightness == 0 || brightness == 255) fadeAmount = -fadeAmount;
  delay(30);
}</code></pre>

      <h2 id="s2">DC Motor Speed</h2>
      <p>Drive a DC motor via an L298N or DRV8833 H-bridge with PWM on the enable pin. Higher duty cycle = more average current = higher RPM.</p>
      <pre><code>int motorPin = 5;
analogWrite(motorPin, 128);  // ~50% speed</code></pre>

      <h2 id="s3">Servo Control</h2>
      <p>Servos use PWM at 50 Hz with pulse widths between 1 ms (0°) and 2 ms (180°). The Arduino Servo library handles all of this automatically.</p>
      <pre><code>#include &lt;Servo.h&gt;
Servo myServo;

void setup() {
  myServo.attach(9);     // Servo signal wire to pin 9
}
void loop() {
  myServo.write(0);      // Rotate to 0 degrees
  delay(1000);
  myServo.write(90);     // Rotate to 90 degrees
  delay(1000);
  myServo.write(180);    // Rotate to 180 degrees
  delay(1000);
}</code></pre>
HTML
  ],

  // Fallback for any unknown slug
  '__default__' => [
    'title'        => 'Article Not Found',
    'cat'          => 'Blog',
    'catColor'     => '#7a7068',
    'author'       => 'VoltCore',
    'avatar'       => '',
    'date'         => '–',
    'time'         => '–',
    'views'        => '–',
    'heroEmoji'    => '',
    'heroGradient' => 'linear-gradient(135deg,#1a1612,#2d2620)',
    'tags'         => [],
    'toc'          => [],
    'content'      => '<p>Sorry, this article could not be found. <a href="blog.php" style="color:var(--accent)">← Return to Blog</a></p>',
  ],
];

/* ══ RELATED POSTS ══ */
$RELATED = [
  'Tutorials'     => ['pwm-guide', 'soldering-101', 'esp32-guide'],
  'Technical'     => ['i2c-spi-uart', 'mqtt-esp32', 'esp32-guide'],
  'AI & IoT'      => ['tflite-esp32', 'chatgpt-robot-arm', 'iot-power-tips'],
  'Tips & Tricks' => ['iot-power-tips', 'pwm-guide', 'soldering-101'],
  'Reviews'       => ['arduino-r3-vs-r4', 'esp32-guide', 'i2c-spi-uart'],
  'Projects'      => ['smart-home-hub', 'chatgpt-robot-arm', 'tflite-esp32'],
];
