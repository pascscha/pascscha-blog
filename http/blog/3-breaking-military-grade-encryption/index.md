# Breaking Military Grade Encryption to Animate my Name

When you visit [my website](https://pascscha.ch), you're greeted with an unconventional banner - a diagram of AES in CBC mode, animated to reveal my name letter by letter. While I must admit that the green hacker font is a bit flashy, there is some actual meaningful cryptography going on under the hood. It's a live demonstration of a padding oracle attack against a (purposefully) vulnerable script I wrote that employs "Military Grade" encryption.

## Military Grade Encryption

But what is Military Grade Encryption? This term is often thrown around in marketing materials, insinuating unbreakable security. However, what's often meant by this is simply the use of AES (Advanced Encryption Standard). To be fair, AES is indeed a robust encryption algorithm. However, it's a block cipher, meaning it operates on fixed-size blocks of data (typically 128 bits). All that AES does is provide an invertible function that transforms an input block of data together with a key into an output block of data. That in itself is not particularly useful, as in general, one would want to encrypt more than one block of data. That is why block ciphers like AES usually come with a mode of operation to extend the length of the data they can encrypt.

### AES Modes of Operation

The simplest mode, Electronic Codebook (ECB), encrypts each block independently. Encrypting a large file will split it into smaller blocks, each of which will be encrypted using AES with the encryption key.

![In AES-ECB the Plaintext is split into blocks and each block encrypted separately.](img/AES-ECB.webp)

Sounds intuitive, right? However, there is an issue with that approach. Namely, if two incoming plaintext blocks are exactly the same, then the corresponding ciphertext blocks will also be the same. So in some cases, you can infer patterns of the plaintext just by viewing the ciphertext. A good example of this is the encryption of an image as follows:

![Example taken from Wikipedia](img/TUX-ECB-CBC.webp)

This is why we need some sort of randomness for each block to avoid this issue. One method that is still very commonly used today is AES-CBC (Cipher Block Chaining) mode. When encrypting, it does not pass the plaintext blocks directly through AES, but instead XORs the first block with a unique initialization vector. Then each following block is XORed with the encrypted output of the previous block. That way, the input to the AES block cipher changes every time, even if all plaintext blocks were to be the same:

![In AES-CBC mode plaintext blocks are XORed with the previous ciphertext block.](img/AES-CBC.webp)

Modern modes are more complex than that and can offer even more features, such as [Authenticated Encryption](https://en.wikipedia.org/wiki/Authenticated_encryption). As of 2024, AES-GCM (Galois/Counter Mode) would be a good choice for many applications. However, AES-CBC is still widely used and can be secure in some settings, but it is susceptible to certain pitfalls.

### The Need for Padding

Block ciphers require input of a specific length. But we want to send plaintexts of any number of bytes. To make sure that the plaintext fits neatly into blocks, we can apply padding. For this, [PKCS#7 Padding](https://en.wikipedia.org/wiki/Padding_(cryptography)#PKCS#5_and_PKCS#7) is commonly used. This method checks how many bytes we need to fill up to the next block, then fills that block with bytes containing this value. For example:

- If we need 3 bytes of padding, we add `[0x03, 0x03, 0x03]`
- If we need 1 byte, we add `[0x01]`
- If the plaintext is already a multiple of the block size, we add a full block of padding

This way, the last byte always indicates the padding length, making it easy to remove after decryption. However, if the padding is not correct, i.e., the last byte is `0x03`, but the two bytes before that are not also `0x03`, then the padding is invalid, and an error is thrown.

This is where a significant vulnerability can arise. If an attacker can submit some ciphertext for decryption and discern whether there was a padding issue or not, they could potentially recover the plaintext of any ciphertext. This is known as a padding oracle attack.

## The vulnerable "MilitaryGradeEncryptor"

To illustrate all of this, we can now finally have a look at our [MilitaryGradeEncryptor](/js/banner/MilitaryGradeEncryptor.js). This is a JavaScript class I intentionally made vulnerable to a padding oracle attack as described above. It's filled with misinformed comments - don't take any of them seriously.

The class is initialized with some plaintext, which is then stored within the class, together with a new random key. In our attack, we will never directly access that plaintext or key.

```js
/**
* MilitaryGradeEncryptor - Your one-stop shop for Zero Knowledge Encryption!
* Our state-of-the-art algorithm guarantees military grade security, ensuring that your data is as safe as it can be.
*/
class MilitaryGradeEncryptor {
    /**
     * Initialize a new instance of MilitaryGradeEncryptor with the plaintext to be encrypted.
     * @param {string} plaintext - The text to be encrypted.
     */
    constructor(plaintext) {
        // Default plaintext
        if (!plaintext) {
            plaintext = "Pascal Schärli";
        }

        let encoder = new TextEncoder();
        this.data = encoder.encode(plaintext);

        // Generate a 256-bit military-grade key, even we don't know what it's going to be!
        this.key = crypto.getRandomValues(new Uint8Array(32));
        this.imported_key = null;
    }
```

The object is an encryptor, so we need to expose some sort of encryption function. This will encrypt the plaintext with its secret key, using a random initialization vector. Finally, both the initialization vector and ciphertext are concatenated and returned:

```js
    /**
     * Encrypt the plaintext using our military grade 128-bit AES encryption and return the zero-knowledge encrypted secret.
     * @returns {Promise < Uint8Array >} - The encrypted text as an ArrayBuffer of bytes.
     */
    async getEncryptedSecret() {
        // Initialization Vector (IV) is generated using a Cryptographically Secure Pseudo-Random Number Generator (CSPRNG), ensuring the same plaintext never produces the same ciphertext.
        let iv = crypto.getRandomValues(new Uint8Array(16));

        // Import the key into WebCrypto for use in decrypting our data.
        if (this.imported_key === null) {
            this.imported_key = await crypto.subtle.importKey(
                "raw", this.key, { name: "AES-CBC" }, false, ["encrypt", "decrypt"]
            );
        }

        // Military-Grade encryption
        let blocks = new Uint8Array(await crypto.subtle.encrypt(
            { name: "AES-CBC", iv: iv },
            this.imported_key,
            this.data
            ));
        let ciphertext = new Uint8Array(iv.length + blocks.length);
        ciphertext.set(iv);
        ciphertext.set(blocks, iv.length);

        // Encrypted Cipher, with Indistinguishability even under Chosen Plaintext (IND-CPA), look it up!
        return ciphertext;
    }
```

Now we're getting to the juicy bit. The class also exposes a function that serves as a padding oracle. The user can provide it with any ciphertext, which will be decrypted by the class, but not returned to the user. The only thing returned by it is whether the decryption was successful or not. My implementation uses the premise that this is some sort of [Authenticated Encryption](https://en.wikipedia.org/wiki/Authenticated_encryption), like AES-GCM would offer. However, this is not actually the case. What we do gain, however, is an oracle that tells us if the padding of the decryption of any ciphertext we provide is valid or not.

```js
    /**
    * Verify integrity of ciphertext by checking if it can be decrypted without error. I think that was called authenticated Encryption or something
    * @param {Uint8Array} ciphertext - The encrypted text to verify.
    * @returns {Promise < boolean >} - True if the decryption succeeds without errors, false otherwise.
    */
    async isValidCiphertext(ciphertext) {
        try {
            // Import the key into WebCrypto for use in decrypting our data.
            if (this.imported_key === null) {
                this.imported_key = await crypto.subtle.importKey(
                    "raw", this.key, { name: "AES-CBC" }, false, ["encrypt", "decrypt"]
                );
            }

            // Split the ciphertext into the IV and the encrypted blocks for easy decryption.
            let iv = ciphertext.subarray(0, 16);
            let blocks = ciphertext.subarray(16);

            // Decrypt the data
            await crypto.subtle.decrypt({ name: "AES-CBC", iv: iv }, this.imported_key, blocks);

            // If decryption succeeds, we know the ciphertext is valid!
            return true;

        } catch (err) {
            // See if there was a padding error, in which case the ciphertext is not valid! Go away hackers!
            if (err.name === "OperationError") {
                return false;
            } else {
                throw err;
            }
        }
    }
}
```

## Exploiting the Padding Oracle

Now let me show you how an attacker could use just this validation function to recover the full plaintext of any ciphertext. When we start, all we have is some initialization vector and ciphertext, but we don't know how they will be decrypted:

![We received an Initialization Vector and Ciphertext, and don't know how it decrypts.](img/AES-CBC-0.svg)

Our goal now is to learn the value of the "Block Cipher Output". Once we learn that value, we can XOR it with the Initialization Vector we received and obtain the Plaintext. For this, we are ignoring the original Initialization Vector and starting over with a new one, which we set to all zeros. We will pass the all-zero Initialization Vector with the unchanged Ciphertext Block to our padding oracle, and we will likely encounter a padding error.

![Changing the Initialization Vector to all zeros will likely give us a padding error.](img/AES-CBC-1.svg)

Now we will try to achieve a valid padding of 1 byte. A PKCS#7 padding of one byte means that the last byte of the plaintext must be a `0x01`. To achieve this, we can simply try out all possible 256 values for the last Initialization Vector byte. There will always be one value that will give us byte `0x01` at the end of the plaintext, and therefore no padding error. Knowing that, we can then also deduce the last byte of the block cipher output to be `0x01` XORed with the last byte of the new initialization vector.

![We found that setting the last byte of the Initialization Vector to `0xC7` will give no padding error. Therefore the last block of the block cipher output must be `0xC7 xor 0x01 = 0xC6`.](img/AES-CBC-2.svg)

Now we move on to the second-to-last byte. To do this, we will try to achieve a padding of length two, so the last two bytes of the plaintext have to be `0x02, 0x02` for a valid padding. We change the last byte of the Initialization Vector to make the last byte `0x02`. Again, we will likely have a padding error now.

![We set the last byte of the Initialization vector to `0x02 xor 0xC6 = 0xC4`, to make the last plaintext byte be `0x02`. Because the second last plaintext byte is likely wrong, there will probably be a padding error.](img/AES-CBC-3.svg)

Let's get rid of that padding error again! We'll try all possible values of the second-to-last byte of the Initialization Vector until we find a value where the padding error disappears. Just like before, we now also learn the value of the second-to-last Block Cipher Output byte.

![After brute forcing the second-to-last Initialization Vector we found that `C5` gives no padding error. Therefore the secont-to-last byte of the block cipher output has to be `0xC5 xor 0x02 = 0xC7`.](img/AES-CBC-4.svg)

We can now repeat this process for the third, fourth, fifth last byte and so on, until we successfully find an initialization vector that produces a full block of padding, at which point we will have learned the full Block Cipher Output.

![After brute forcing the first Initialization Vector byte, we now know the full Block Cipher Output.](img/AES-CBC-5.svg)

And now comes the time to shine for the original Initialization Vector that we overwrote with zeros at the beginning. When we replace our current Initialization Vector with the original one, we will have recovered the real plaintext. In this case, it's the bytes encoding the text "Pascal Schärli", which neatly fits into one block with a single byte of padding.

![Substituting the Initialization Vector with the original one reveals the plaintext.](img/AES-CBC-6.svg)

This process is implemented in [paddingOracleDemo.js](/js/banner/paddingOracleDemo.js), which uses the `isValidCiphertext` function of the `MilitaryGradeEncryptor` to perform a padding oracle attack as described here, and along the way updates the SVG of my banner to create the animation.

## Conclusions

As you can see, AES by itself does not guarantee secure cryptography. AES-CBC mode is still widely used, despite its possible pitfalls when implemented incorrectly. In fact, any algorithm, no matter how secure it is, can be used in an insecure manner, so any claims of security based solely on the set of algorithms being used should be taken with a grain of salt.

When using AES, you're usually better off using other modes of operations, such as AES-GCM, which, besides mitigating the padding oracle attack, also offers [Authenticated Encryption](https://en.wikipedia.org/wiki/Authenticated_encryption). This means that you can detect if a ciphertext was tampered with, providing an additional layer of security.

It's crucial to remember that cryptography is a complex field, and even seemingly secure algorithms can be vulnerable when implemented or used incorrectly. Always consult with cryptography experts and use well-vetted libraries and protocols when implementing cryptographic systems in real-world applications.
