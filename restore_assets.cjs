const fs = require('fs');
const path = require('path');

const publicDir = path.join(__dirname, 'public/assets/img');
const sourceImg = path.join(publicDir, 'profile-bg.jpg'); // Source for dummy JPGs
const sourceLogo = path.join(publicDir, 'logo.png'); // Source for dummy PNGs

// Directories to create
const dirs = [
    'hope',
    'icon',
    'team',
    'testimonial',
    'partner'
];

// File mappings (filename -> type)
const files = {
    'hope': [
        '_U9A7700.jpg',
        '_U9A8033.jpg',
        '259A2654 copy.JPG',
        '123.jpg',
        '43.jpg',
        'HOPEDREAM_31.jpg',
        'img_66e00909f32259.92841743.jpg',
        'first.jpg',
        'poster-profil.jpg',
        'stRe.jpg',
        '_U9A7765.jpg',
        'r456.jpg',
        'aka465.jpg',
        'REMISE UEA (29).jpg',
        'ra123k.jpg',
        'ogg.jpg',
        'IMG_1135 -BG (1).jpg',
        'h1a.jpg',
        'IMG_1135 -BG.jpg'
    ],
    'icon': [
        'book.svg',
        'teacher.svg',
        'library.svg',
        'building-2.svg',
        'exchange-idea.svg',
        'open-book.svg',
        'course.svg',
        'global-education.svg',
        'teacher-2.svg',
        'graduation.svg',
        'art.svg',
        'monitor.svg',
        'health.svg',
        'data.svg',
        'online-course.svg',
        'course-material.svg'
    ],
    'team': [
        '01.jpg', '02.jpg', '03.jpg', '04.jpg'
    ],
    'testimonial': [
        '01.jpg', '02.jpg', '03.jpg', '04.jpg', '05.jpg'
    ],
    'partner': [
        'logomts.png'
    ]
};

// SVG Placeholder Content
const svgContent = `<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg">
 <rect width="100%" height="100%" fill="#ddd"/>
 <circle cx="50" cy="50" r="40" stroke="green" stroke-width="4" fill="yellow" />
 <text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" font-family="sans-serif" font-size="20">ICON</text>
</svg>`;

// Ensure directories exist
dirs.forEach(d => {
    const p = path.join(publicDir, d);
    if (!fs.existsSync(p)) {
        fs.mkdirSync(p, { recursive: true });
        console.log(`Created directory: ${d}`);
    }
});

// Create files
Object.keys(files).forEach(folder => {
    files[folder].forEach(file => {
        const dest = path.join(publicDir, folder, file);

        if (file.endsWith('.svg')) {
            fs.writeFileSync(dest, svgContent);
            console.log(`Created SVG: ${folder}/${file}`);
        } else if (file.endsWith('.png')) {
            if (fs.existsSync(sourceLogo)) {
                fs.copyFileSync(sourceLogo, dest);
                console.log(`Created PNG (from logo): ${folder}/${file}`);
            } else {
                console.log(`Skipped PNG ${file}, source missing`);
            }
        } else {
            // JPG/Default
            if (fs.existsSync(sourceImg)) {
                fs.copyFileSync(sourceImg, dest);
                console.log(`Created JPG (from bg): ${folder}/${file}`);
            } else {
                // Fallback if sourceImg missing
                fs.writeFileSync(dest, 'DUMMY IMAGE CONTENT');
                console.log(`Created dummy text file: ${folder}/${file}`);
            }
        }
    });
});

console.log('Restoration complete.');
