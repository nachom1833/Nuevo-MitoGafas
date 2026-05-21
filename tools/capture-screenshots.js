const puppeteer = require('puppeteer');
const path = require('path');
const fs = require('fs');

(async () => {
  const browser = await puppeteer.launch({ headless: true });
  const page = await browser.newPage();

  const url = `file://${path.resolve(__dirname, '..', 'index.html')}`;
  const outDir = path.resolve(__dirname, '..', 'screenshots');
  if (!fs.existsSync(outDir)) fs.mkdirSync(outDir);

  const viewports = [
    { name: 'desktop', width: 1366, height: 800 },
    { name: 'tablet', width: 1024, height: 768 },
    { name: 'mobile', width: 375, height: 812 },
  ];

  for (const vp of viewports) {
    await page.setViewport({ width: vp.width, height: vp.height });
    await page.goto(url, { waitUntil: 'networkidle2' });
    await page.waitForTimeout(500);
    const outPath = path.join(outDir, `screenshot-${vp.name}.png`);
    await page.screenshot({ path: outPath, fullPage: true });
    console.log('Saved', outPath);
  }

  await browser.close();
})();
