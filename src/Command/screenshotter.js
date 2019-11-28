const puppeteer = require('puppeteer');
let screenname = process.argv[3] ? process.argv[3] : 'screenshot-'+Math.floor(Date.now() / 1000);
console.log(screenname);
console.log(process.argv[2]);
(async () => {
    const browser = await puppeteer.launch();
    const page = await browser.newPage();
    await page.goto(process.argv[2]);
    await page.waitFor(2000);
    const element = await page.$$('.enlighter-default.enlighter-v-standard.enlighter-t-dracula.enlighter-linenumbers');
    // console.log(element)
    // console.log(element)
    await element[0].screenshot({path: screenname.endsWith('.png') ? screenname : screenname + '.png'});

    await browser.close();
})();
