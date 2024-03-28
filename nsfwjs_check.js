const fs = require('fs');
const nsfwjs = require('nsfwjs');

(async () => {
    // Load the NSFWJS model
    const model = await nsfwjs.load();

    // Get the image data from the command line argument
    const imageDataBase64 = process.argv[2];
    const imageDataBuffer = Buffer.from(imageDataBase64, 'base64');

    // Classify the image
    const { className, probability } = await nsfwjs.classify(model, imageDataBuffer);

    // Output the result
    console.log(className === 'Neutral' && probability > 0.7 ? 'true' : 'false');
})();
