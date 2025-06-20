<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./index.css">
    <title>Cryptography Toolbox</title>
</head>
<body>
    <div class="header">
        <h2>Cryptography Toolbox v1.6</h2>
    </div>

    <div class="modes_box">
        <ul class="modes_list" id="modes_list">
            <li><button onclick="showDescription('Caesar')">Caesar Cipher</button></li>
            <li><button onclick="showDescription('Atbash')">Atbash Cipher</button></li>
            <li><button onclick="showDescription('Vigenere')">Vigenère Cipher</button></li>
            <li><button onclick="showDescription('RSA')">RSA Cipher</button></li>
            <li><button onclick="showDescription('AES')">AES (Advanced Encryption Standard)</button></li>
            <!-- <li><button onclick="showDescription('Blowfish')">Blowfish Cipher</button></li> -->
        </ul>
    </div>

    <div class="main_box">
        <div class="encrypt_box">
            <textarea name="encrypt" id="encrypt" class="encrypt_text" placeholder="Insert text to encrypt here"></textarea>
            <div class="btn_space">
                <button class="clear" onclick="document.getElementById('encrypt').value = '';">Clear</button>
                <div id="extra_btns_space1" class="extra_btns_space"></div>
            </div>
        </div>

        <div class="arrow">
            <img src="./assets/arrow_right.png" alt="Right Arrow" height="50" width="50" class="arrow_img" id="arrow_img">
            <button id="go" class="go">Encrypt</button>
        </div>

        <div class="decrypt_box">
            <textarea name="decrypt" id="decrypt" class="decrypt_text" placeholder="Insert text to decrypt here"></textarea>
            <div class="btn_space">
            <button class="clear" onclick="document.getElementById('decrypt').value = '';">Clear</button>
            <div id="extra_btns_space2" class="extra_btns_space"></div>
            </div>
        </div>
    </div>

    <div class="description_box">
        <h3 id="desc_mode" class="desc_mode">Please Select A Cipher Mode</h3>
        <p id="desc_text" class="desc_text"></p>
    </div>

    <div class="footer">
        <p title="made with spite and many sleepless nights 🩶">Cryptography Toolbox &copy; 2025    |   CVSU-CCC    |   BSIT-3C    |    ITEC106 FINAL PROJECT</p>
        <button id="about" class="about" onclick="
            alert('Created and Designed by:\nAdriel P.\nAbby M.\n\nand others\n\nSpecial Thanks to:\nno one');
        ">About</button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/node-forge@1.0.0/dist/forge.min.js"></script>
    <script src="/ciphers.js"></script>
    <script>

        // encrypt or decrypt mode
        let enc_dec = null;

        // click on left box
        document.getElementById('encrypt').addEventListener('click', function() {
        document.getElementById('arrow_img').src = './assets/arrow_right.png';
        document.getElementById('go').textContent = 'Encrypt';
        enc_dec = 'Encrypt';
        });

        // click on right box
        document.getElementById('decrypt').addEventListener('click', function() {
        document.getElementById('arrow_img').src = './assets/arrow_left.png';
        document.getElementById('go').textContent = 'Decrypt';
        enc_dec = 'Decrypt';
        });

        // for the shift buttons on caesar mode
        function changeShift(id, delta) {
            const input = document.getElementById(id);
            let value = parseInt(input.value, 10) || 1;
            value += delta;
            if (value < parseInt(input.min)) value = parseInt(input.min);
            if (value > parseInt(input.max)) value = parseInt(input.max);
            input.value = value;
        }
   
        // clear extra buttons when switching modes
        function clearExtraBtns() {
            document.getElementById('extra_btns_space1').innerHTML = '';
            document.getElementById('extra_btns_space2').innerHTML = '';
        }

        // active mode listener
        document.querySelectorAll('.modes_list button').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.modes_list button').forEach(b => b.classList.remove('mode-selected'));
                    this.classList.add('mode-selected');
                });
        });

        // variable for the currently sel. mode
        let selectedMode = null;

        // description box + extra buttons for each mode
        function showDescription(mode) {
            selectedMode = mode;
            clearExtraBtns()
            let title = '';
            let desc = '';

            if (mode === 'Caesar') {
                title = 'Caesar Cipher';
                desc = 'The Caesar Cipher is a simple substitution cipher where each letter in the plaintext is shifted by a fixed number of positions down the alphabet. It is one of the oldest known encryption techniques and is easy to both encrypt and decrypt with the correct shift value.';
                
                document.querySelectorAll('.extra_btns_space').forEach((el, i) => {
                    const inputId = `shift_counter${i+1}`;
                    el.innerHTML = `
                        <label style="color:#ffb700;margin-right:5px;">Shift:</label>
                        <button type="button" class="shift-btn" onclick="changeShift('${inputId}', -1)">&#8722;</button>
                        <input type="number" id="${inputId}" value="3" min="1" max="25" class="shift-input">
                        <button type="button" class="shift-btn" onclick="changeShift('${inputId}', 1)">&#43;</button>
                    `;
                });

            } else if (mode === 'Atbash') {
                title = 'Atbash Cipher';
                desc = 'The Atbash Cipher is a monoalphabetic substitution cipher that reverses the standard alphabet, so the first letter becomes the last, the second becomes the second to last, and so on. It is a simple and ancient cipher, often used for basic obfuscation.';
            } else if (mode === 'Vigenere') {
                title = 'Vigenère Cipher';
                desc = 'The Vigenère Cipher is a polyalphabetic substitution cipher that uses a keyword to determine the shift for each letter in the plaintext. This makes it more secure than simple ciphers, as the pattern of encryption changes throughout the message.';
            
                document.querySelectorAll('.extra_btns_space').forEach((el, i) => {
                    el.innerHTML = `
                        <label for="vigenere_keyword${i+1}" style="color:#ffb700;margin-right:5px;">Keyword:</label>
                        <input type="text" id="vigenere_keyword${i+1}" class="vigenere-input" value="KEY" maxlength="20" style="width:100px;">
                    `;
                });
            
            } else if (mode === 'RSA') {
                title = 'RSA Cipher';
                desc = 'RSA is a modern asymmetric encryption algorithm that uses a pair of keys: a public key for encryption and a private key for decryption. It is widely used for secure data transmission, digital signatures, and cryptographic protocols.';
            
                // Public key (encryption) inputs in extra_btns_space1
                document.getElementById('extra_btns_space1').innerHTML = `
                    <label for="rsa_modulo1" style="color:#ffb700;margin-right:5px;">Modulo:</label>
                    <input type="text" id="rsa_modulo1" class="rsa-input" value="3233" placeholder="modulo" style="width:90px;">
                    <label for="rsa_public1" style="color:#ffb700;margin:0 5px 0 10px;">Public Key:</label>
                    <input type="text" id="rsa_public1" class="rsa-input" value="17" placeholder="public exp" style="width:60px;">
                `;

                // Private key (decryption) inputs in extra_btns_space2
                document.getElementById('extra_btns_space2').innerHTML = `
                    <label for="rsa_modulo2" style="color:#ffb700;margin-right:5px;">Modulo:</label>
                    <input type="text" id="rsa_modulo2" class="rsa-input" value="3233" placeholder="modulo" style="width:90px;">
                    <label for="rsa_private2" style="color:#ffb700;margin:0 5px 0 10px;">Private Key:</label>
                    <input type="text" id="rsa_private2" class="rsa-input" value="413" placeholder="private exp" style="width:60px;">
                `;
            
            } else if (mode === 'AES') {
                title = 'AES (Advanced Encryption Standard)';
                desc = 'AES is a symmetric block cipher that encrypts data in fixed-size blocks using a shared secret key. It is the standard for secure data encryption worldwide, known for its speed, security, and efficiency in both hardware and software.';
            
                document.querySelectorAll('.extra_btns_space').forEach((el, i) => {
                    el.innerHTML = `
                        <label for="aes_key${i+1}" style="color:#ffb700;margin-right:5px;">Key:</label>
                        <input type="text" id="aes_key${i+1}" class="aes-input" value="secretkey1234567" maxlength="16" placeholder="key" style="width:140px;">
                    `;
                });
            
            } else if (mode === 'Blowfish') {
                title = 'Blowfish Cipher';
                desc = 'Blowfish is a symmetric-key block cipher designed for fast and secure encryption. It features a variable-length key and is known for its simplicity and effectiveness, making it suitable for a wide range of applications.';
            
                document.querySelectorAll('.extra_btns_space').forEach((el, i) => {
                    el.innerHTML = `
                        <label for="blowfish_key${i+1}" style="color:#ffb700;margin-right:5px;">Key:</label>
                        <input type="text" id="blowfish_key${i+1}" class="blowfish-input" value="blowfishkey12345" maxlength="16" placeholder="key" style="width:140px;">
                    `;
                });

            } else {
                title = 'Unknown Mode';
                desc = 'No description available.';
            }

            document.getElementById('desc_mode').textContent = title;
            document.getElementById('desc_text').textContent = desc;
        }

        document.getElementById('go').addEventListener('click', function() {
            const goMode = enc_dec;
            const selMode = selectedMode;
            let text;

            // check for action mode
            if (goMode === 'Encrypt') {
                text = document.getElementById('encrypt').value;
            } else if (goMode === 'Decrypt') {
                text = document.getElementById('decrypt').value;
            } else {
                alert('Please select a mode and an action (Encrypt/Decrypt) before proceeding.');
                return;
            }
            
            //check for selected mode
            if (selMode === 'Caesar') {
                if (goMode === 'Encrypt') {
                    const shift = parseInt(document.getElementById('shift_counter1').value, 10);
                    const encryptedText = caesarCipher(text, shift);
                    document.getElementById('decrypt').value = encryptedText;
                } else if (goMode === 'Decrypt') {
                    const shift = parseInt(document.getElementById('shift_counter2').value, 10);
                    const decryptedText = caesarCipher(text, -shift);
                    document.getElementById('encrypt').value = decryptedText;
                }


            } else if (selMode === 'Atbash') {
               if (goMode === 'Encrypt') {
                    const encryptedText = atbashCipher(text);
                    document.getElementById('decrypt').value = encryptedText;
                } else if (goMode === 'Decrypt') {
                    const decryptedText = atbashCipher(text);
                    document.getElementById('encrypt').value = decryptedText;
                }
            } else if (selMode === 'Vigenere') {
                const key = document.getElementById(goMode === 'Encrypt' ? 'vigenere_keyword1' : 'vigenere_keyword2').value;
                if (goMode === 'Encrypt') {
                    const encryptedText = vigenereCipher(text, key);
                    document.getElementById('decrypt').value = encryptedText;
                } else if (goMode === 'Decrypt') {
                    const decryptedText = vigenereCipher(text, key, true);
                    document.getElementById('encrypt').value = decryptedText;
                }
            
            } else if (selMode === 'RSA') {
                if (goMode === 'Encrypt') {
                    const n = document.getElementById('rsa_modulo1').value;
                    const e = document.getElementById('rsa_public1').value;
                    const encryptedText = rsaEncrypt(text, n, e);
                    document.getElementById('decrypt').value = encryptedText;
                } else if (goMode === 'Decrypt') {
                    const n = document.getElementById('rsa_modulo2').value;
                    const d = document.getElementById('rsa_private2').value;
                    const decryptedText = rsaDecrypt(text, n, d);
                    document.getElementById('encrypt').value = decryptedText;
                }
            
            } else if (selMode === 'AES') {
                const key = document.getElementById(goMode === 'Encrypt' ? 'aes_key1' : 'aes_key2').value;
                if (goMode === 'Encrypt') {
                    // Encrypt
                    const cipher = forge.cipher.createCipher('AES-ECB', forge.util.createBuffer(key));
                    cipher.start();
                    cipher.update(forge.util.createBuffer(text, 'utf8'));
                    cipher.finish();
                    const encrypted = cipher.output.getBytes();
                    // Encode to base64 for display
                    const encryptedText = forge.util.encode64(encrypted);
                    document.getElementById('decrypt').value = encryptedText;
                } else if (goMode === 'Decrypt') {
                    // Decrypt
                    const decipher = forge.cipher.createDecipher('AES-ECB', forge.util.createBuffer(key));
                    decipher.start();
                    // Decode from base64
                    decipher.update(forge.util.createBuffer(forge.util.decode64(text)));
                    decipher.finish();
                    const decryptedText = decipher.output.toString('utf8');
                    document.getElementById('encrypt').value = decryptedText;
                }               
            
            } else if (selMode === 'Blowfish') {
                const key = document.getElementById(goMode === 'Encrypt' ? 'blowfish_key1' : 'blowfish_key2').value;
                if (goMode === 'Encrypt') {
                    // Pad text to multiple of 8 bytes (Blowfish block size)
                    let padded = text;
                    while (forge.util.encodeUtf8(padded).length % 8 !== 0) padded += ' ';
                    const cipher = forge.cipher.createCipher('BF-ECB', forge.util.createBuffer(forge.util.encodeUtf8(key)));
                    cipher.start();
                    cipher.update(forge.util.createBuffer(forge.util.encodeUtf8(padded)));
                    cipher.finish();
                    const encrypted = cipher.output.getBytes();
                    const encryptedText = forge.util.encode64(encrypted);
                    document.getElementById('decrypt').value = encryptedText;
                } else if (goMode === 'Decrypt') {
                    const decipher = forge.cipher.createDecipher('BF-ECB', forge.util.createBuffer(forge.util.encodeUtf8(key)));
                    decipher.start();
                    decipher.update(forge.util.createBuffer(forge.util.decode64(text)));
                    decipher.finish();
                    // Remove padding spaces
                    const decryptedText = decipher.output.toString('utf8').replace(/\s+$/g, '');
                    document.getElementById('encrypt').value = decryptedText;

                    
                }
                console.log('Encrypted:', encryptedText);
                console.log('Decrypted:', decryptedText);
            } else {
                alert('Please select a mode before proceeding.');
                return;
            }
        });

        // bg cycler
        window.onload = function() {
            const images = [
                '1.jpg',
                '2.jpg',
                '3.jpg',
                '4.jpg',
                '5.jpg',
                '6.jpg',
                ];

            const randomImage = images[Math.floor(Math.random() * images.length)];

            document.body.style.backgroundImage = `url('./assets/${randomImage}')`;
            document.body.style.backgroundSize = 'cover';
            document.body.style.backgroundRepeat = 'no-repeat';
            document.body.style.backgroundPosition = 'center';
            };

    </script>
</body>
</html>