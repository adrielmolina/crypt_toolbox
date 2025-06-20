function caesarCipher(str, shift) {
    return str.replace(/[a-z]/gi, function(char) {
        const base = char <= 'Z' ? 65 : 97;
        return String.fromCharCode(
            ((char.charCodeAt(0) - base + shift) % 26) + base
        );
    });
}

function atbashCipher(str) {
    return str.replace(/[a-z]/gi, function(char) {
        const base = char <= 'Z' ? 65 : 97;
        return String.fromCharCode(25 - (char.charCodeAt(0) - base) + base);
    });
}

function vigenereCipher(str, key, decrypt = false) {
    key = key.toUpperCase();
    let j = 0;
    return str.replace(/[a-z]/gi, function(char) {
        const base = char <= 'Z' ? 65 : 97;
        const k = key[j % key.length].charCodeAt(0) - 65;
        let shift = decrypt ? 26 - k : k;
        j++;
        return String.fromCharCode(((char.charCodeAt(0) - base + shift) % 26) + base);
    });
}

function rsaEncrypt(plain, n, e) {
    // Only works for numbers or single characters for demo!
    let codes = [];
    for (let i = 0; i < plain.length; i++) {
        let m = plain.charCodeAt(i);
        let c = BigInt(m) ** BigInt(e) % BigInt(n);
        codes.push(c.toString());
    }
    return codes.join(' ');
}

function rsaDecrypt(cipher, n, d) {
    let chars = cipher.split(' ').map(c => {
        let m = BigInt(c) ** BigInt(d) % BigInt(n);
        return String.fromCharCode(Number(m));
    });
    return chars.join('');
}

