const puppeteer = require('puppeteer');

(async () => {
    const browser = await puppeteer.launch({
        headless: false,  // biar bisa lihat
        args: ['--no-sandbox']
    });

    const page = await browser.newPage();

    // Set viewport iPhone SE
    await page.setViewport({ width: 375, height: 667 });

    // Buka landing page
    await page.goto('http://telkom.test', { waitUntil: 'networkidle2' });

    // Tunggu halaman load
    await new Promise(r => setTimeout(r, 3000));

    // Screenshot sebelum klik hamburger
    await page.screenshot({ path: 'test-mobile-before.png', fullPage: false });
    console.log('📸 Screenshot sebelum klik hamburger: test-mobile-before.png');

    // Cek jQuery loaded
    const jqueryLoaded = await page.evaluate(() => typeof jQuery !== 'undefined');
    console.log('jQuery loaded:', jqueryLoaded);

    // Cek apakah rsmenu-main.js loaded
    const scripts = await page.evaluate(() => {
        return Array.from(document.querySelectorAll('script[src]')).map(s => s.src);
    });
    console.log('Scripts loaded:', scripts.filter(s => s.includes('rs') || s.includes('menu') || s.includes('jquery')));

    // Cek initial state hamburger
    const initialState = await page.evaluate(() => {
        const toggle = document.querySelector('a.rs-menu-toggle');
        const menu = document.querySelector('.rs-menu');
        return {
            toggleExists: !!toggle,
            toggleClasses: toggle?.className,
            menuExists: !!menu,
            menuClasses: menu?.className,
            menuHeight: menu ? window.getComputedStyle(menu).height : null
        };
    });
    console.log('Initial state:', JSON.stringify(initialState, null, 2));

    // Cari dan klik hamburger button
    const hamburger = await page.$('a.rs-menu-toggle');
    if (hamburger) {
        console.log('\n✅ Hamburger button ditemukan!');

        // Method 1: Gunakan jQuery click langsung (lebih reliable)
        if (jqueryLoaded) {
            console.log('Mengklik via jQuery...');
            await page.evaluate(() => {
                $('a.rs-menu-toggle').trigger('click');
            });
        } else {
            console.log('jQuery tidak ada, menggunakan native click...');
            await hamburger.click();
        }

        // Tunggu animasi jQuery (300ms duration + buffer)
        await new Promise(r => setTimeout(r, 500));

        // Screenshot setelah klik hamburger
        await page.screenshot({ path: 'test-mobile-after.png', fullPage: false });
        console.log('📸 Screenshot setelah klik hamburger: test-mobile-after.png');

        // Cek apakah menu visible
        const menuVisible = await page.evaluate(() => {
            const menu = document.querySelector('.rs-menu');
            if (!menu) return { exists: false };
            const style = window.getComputedStyle(menu);
            return {
                exists: true,
                height: style.height,
                overflow: style.overflow,
                display: style.display,
                className: menu.className
            };
        });
        console.log('\n📊 Menu state SETELAH klik:', JSON.stringify(menuVisible, null, 2));

        // Cek hamburger class
        const hamburgerState = await page.evaluate(() => {
            const toggle = document.querySelector('a.rs-menu-toggle');
            return toggle ? toggle.className : 'not found';
        });
        console.log('Hamburger class SETELAH klik:', hamburgerState);

        // Cek visibility nav-menu items
        const navItems = await page.evaluate(() => {
            const items = document.querySelectorAll('.rs-menu .nav-menu > li');
            return Array.from(items).map(li => {
                const style = window.getComputedStyle(li);
                return {
                    text: li.querySelector('a')?.textContent?.trim(),
                    display: style.display,
                    visibility: style.visibility
                };
            });
        });
        console.log('Nav items:', JSON.stringify(navItems, null, 2));

        // Cek apakah menu terlihat (height > 0)
        const menuOpen = menuVisible.exists && menuVisible.height !== '0px' && !(menuVisible.className || '').includes('rs-menu-close');
        console.log('\n🎯 Menu terbuka:', menuOpen ? '✅ YA' : '❌ TIDAK');

        if (!menuOpen) {
            console.log('\n⚠️  DIAGNOSA:');
            console.log('- Class menu:', menuVisible.className);
            console.log('- Height:', menuVisible.height);
            console.log('Menu tetap tertutup setelah klik. Kemungkinan:');
            console.log('  1. jQuery click handler tidak ter-trigger');
            console.log('  2. CSS mengoverride height/overflow');
            console.log('  3. JavaScript error di console');
        }
    } else {
        console.log('❌ Hamburger button TIDAK ditemukan!');

        // Cek apakah ada .mobile-menu
        const mobileMenu = await page.$('.mobile-menu');
        console.log('.mobile-menu exists:', !!mobileMenu);

        // Cek display mobile-menu
        const mobileMenuStyle = await page.evaluate(() => {
            const el = document.querySelector('.mobile-menu');
            if (!el) return null;
            const style = window.getComputedStyle(el);
            return { display: style.display, visibility: style.visibility };
        });
        console.log('.mobile-menu style:', JSON.stringify(mobileMenuStyle));
    }

    // Cek console errors
    const consoleErrors = [];
    page.on('console', msg => {
        if (msg.type() === 'error') consoleErrors.push(msg.text());
    });

    // Reload dan cek errors
    await page.reload({ waitUntil: 'networkidle2' });
    await new Promise(r => setTimeout(r, 2000));

    if (consoleErrors.length > 0) {
        console.log('\n🔴 Console errors:', consoleErrors);
    } else {
        console.log('\n✅ Tidak ada console errors');
    }

    // Jangan close browser biar bisa inspeksi
    // await browser.close();
    console.log('\n🔍 Browser masih terbuka. Tutup manual untuk selesai.');
})();
