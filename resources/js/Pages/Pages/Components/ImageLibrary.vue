<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
    images: { type: Array, default: () => [] },
    shapes: { type: Array, default: () => [] },
    phrases: { type: Array, default: () => [] },
    pageUuid: { type: String, default: '' },
})

const emit = defineEmits(['addImageToCanvas', 'addShape', 'addText', 'setBackground', 'addQr'])

const tab = ref('images')
const imgCategory = ref('love')
const bgSubtab = ref('solids')

const qrForeground = ref('#000000')
const qrBackground = ref('#ffffff')

const SHAPE_ICONS = {
    heart: 'M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z',
    star: 'M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z',
    circle: 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z',
    moon: 'M12 3a9 9 0 109 9c0-.46-.04-.92-.1-1.36a5.389 5.389 0 01-4.4 2.26 5.403 5.403 0 01-3.14-9.8c-.44-.06-.9-.1-1.36z',
    diamond: 'M12 2L2 12l10 10 10-10L12 2z',
    triangle: 'M12 2L2 22h20L12 2z',
    hexagon: 'M12 2l8.66 5v10L12 22l-8.66-5V7L12 2z',
    cloud: 'M19.35 10.04A7.49 7.49 0 0012 4C9.11 4 6.6 5.64 5.35 8.04A5.994 5.994 0 000 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96z',
    'arrow-right': 'M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z',
    'arrow-left': 'M12 20l1.41-1.41L7.83 13H20v-2H7.83l5.58-5.59L12 4l-8 8z',
    'arrow-up': 'M4 12l1.41 1.41L11 7.83V20h2V7.83l5.59 5.58L20 12l-8-8z',
    'arrow-down': 'M20 12l-1.41-1.41L13 16.17V4h-2v12.17l-5.59-5.58L4 12l8 8z',
    cross: 'M10 2h4v8h8v4h-8v8h-4v-8H2v-4h8z',
    plus: 'M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z',
    check: 'M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z',
    lightning: 'M13 2L3 14h7l-1 8 10-12h-7z',
}

function svgDataUri(svg) {
    return 'data:image/svg+xml,' + encodeURIComponent(svg)
}

const IMAGE_CATEGORIES = {
    love: {
        label: 'Love',
        items: [
            {
                id: 'love-1',
                label: 'Heart',
                svg: () => svgDataUri(`<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200">
                    <defs><linearGradient id="lg1" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#ff6b6b"/><stop offset="100%" style="stop-color:#ee5a24"/>
                    </linearGradient></defs>
                    <path d="M100 180l-12-11C44 131 20 109 20 76c0-25 20-45 45-45 16 0 31 8 35 21 4-13 19-21 35-21 25 0 45 20 45 45 0 33-24 55-68 93z" fill="url(#lg1)"/>
                    <circle cx="70" cy="80" r="4" fill="#fff" opacity=".4"/>
                    <circle cx="115" cy="70" r="3" fill="#fff" opacity=".3"/>
                </svg>`),
            },
            {
                id: 'love-2',
                label: 'Rose',
                svg: () => svgDataUri(`<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200">
                    <defs><radialGradient id="rg1" cx="50%" cy="50%" r="50%">
                        <stop offset="0%" style="stop-color:#ff4757"/><stop offset="100%" style="stop-color:#c0392b"/>
                    </radialGradient></defs>
                    <g transform="translate(100 120)">
                        <path d="M-4 0l-8 40h24z" fill="#27ae60"/>
                        <path d="M-8 40h16l-4 20h-8z" fill="#2ecc71"/>
                        <ellipse cx="0" cy="-40" rx="35" ry="30" fill="#ff4757" opacity=".9"/>
                        <ellipse cx="-12" cy="-50" rx="20" ry="18" fill="#ff6b81" opacity=".8"/>
                        <ellipse cx="10" cy="-48" rx="18" ry="16" fill="#ff4757" opacity=".7"/>
                        <ellipse cx="0" cy="-55" rx="15" ry="12" fill="#ff6b81" opacity=".9"/>
                        <path d="M-25-38Q-35-58-15-65" stroke="#27ae60" stroke-width="3" fill="none"/>
                        <path d="M22-36Q35-55 15-62" stroke="#27ae60" stroke-width="3" fill="none"/>
                    </g>
                </svg>`),
            },
            {
                id: 'love-3',
                label: 'Two Hearts',
                svg: () => svgDataUri(`<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200">
                    <defs>
                        <linearGradient id="h1" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#ff6b6b"/><stop offset="100%" style="stop-color:#ee5a24"/>
                        </linearGradient>
                        <linearGradient id="h2" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#ff9ff3"/><stop offset="100%" style="stop-color:#f368e0"/>
                        </linearGradient>
                    </defs>
                    <path d="M65 165C29 130 15 108 15 80c0-20 16-36 36-36 13 0 25 7 30 17 5-10 17-17 30-17 20 0 36 16 36 36 0 28-14 50-50 85z" fill="url(#h1)" opacity=".85"/>
                    <path d="M140 175c-30-28-40-45-40-67 0-16 13-29 29-29 10 0 20 5 25 13 4-8 14-13 24-13 16 0 29 13 29 29 0 22-10 39-40 67z" fill="url(#h2)" opacity=".9" transform="translate(-15,-5)"/>
                </svg>`),
            },
            {
                id: 'love-4',
                label: 'Couple',
                svg: () => svgDataUri(`<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200">
                    <defs><linearGradient id="sg" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#ff9ff3"/><stop offset="100%" style="stop-color:#ff6b6b"/>
                    </linearGradient></defs>
                    <circle cx="75" cy="55" r="18" fill="#ffda79"/>
                    <circle cx="125" cy="55" r="18" fill="#ffda79"/>
                    <path d="M75 110c-18 0-28 18-35 35-3 7 2 15 10 15h50c8 0 13-8 10-15-7-17-17-35-35-35z" fill="#f19066"/>
                    <path d="M125 110c-18 0-28 18-35 35-3 7 2 15 10 15h50c8 0 13-8 10-15-7-17-17-35-35-35z" fill="#f19066"/>
                    <path d="M58 73c-2 0-4-1-5-3-2 5-7 8-13 8s-10-3-13-8c1 2-1 3-3 3-5 0-8-6-8-12 0-18 24-30 24-30s24 12 24 30c0 6-3 12-8 12z" fill="#ffda79" opacity=".5"/>
                    <path d="M142 73c-2 0-4-1-5-3-2 5-7 8-13 8s-10-3-13-8c1 2-1 3-3 3-5 0-8-6-8-12 0-18 24-30 24-30s24 12 24 30c0 6-3 12-8 12z" fill="#ffda79" opacity=".5"/>
                    <path d="M85 105l15-20 15 20" stroke="url(#sg)" stroke-width="3" fill="none" stroke-linecap="round"/>
                </svg>`),
            },
        ],
    },
    hope: {
        label: 'Hope',
        items: [
            {
                id: 'hope-1',
                label: 'Sunrise',
                svg: () => svgDataUri(`<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200">
                    <defs><linearGradient id="sk" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" style="stop-color:#f39c12"/><stop offset="50%" style="stop-color:#f1c40f"/>
                        <stop offset="100%" style="stop-color:#e67e22"/>
                    </linearGradient></defs>
                    <rect width="200" height="200" fill="#2c3e50"/>
                    <circle cx="100" cy="110" r="50" fill="url(#sk)" opacity=".9"/>
                    <circle cx="100" cy="110" r="35" fill="#f1c40f"/>
                    <path d="M0 140q20-30 50-10t50-20 50 10 50-20v70H0z" fill="#2c3e50"/>
                    <path d="M100 80v-8m0 64v-8m-28-28l6 6m44 0l6-6" stroke="#f1c40f" stroke-width="2" opacity=".6"/>
                </svg>`),
            },
            {
                id: 'hope-2',
                label: 'Butterfly',
                svg: () => svgDataUri(`<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200">
                    <defs>
                        <linearGradient id="b1" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#9b59b6"/><stop offset="100%" style="stop-color:#e74c3c"/>
                        </linearGradient>
                        <linearGradient id="b2" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#e74c3c"/><stop offset="100%" style="stop-color:#f39c12"/>
                        </linearGradient>
                    </defs>
                    <ellipse cx="80" cy="90" rx="45" ry="35" fill="url(#b1)" opacity=".8" transform="rotate(-15 80 90)"/>
                    <ellipse cx="120" cy="90" rx="45" ry="35" fill="url(#b2)" opacity=".8" transform="rotate(15 120 90)"/>
                    <ellipse cx="80" cy="120" rx="30" ry="22" fill="url(#b2)" opacity=".7" transform="rotate(10 80 120)"/>
                    <ellipse cx="120" cy="120" rx="30" ry="22" fill="url(#b1)" opacity=".7" transform="rotate(-10 120 120)"/>
                    <rect x="98" y="60" width="4" height="80" rx="2" fill="#2c3e50"/>
                    <line x1="90" y1="70" x2="75" y2="55" stroke="#2c3e50" stroke-width="2"/>
                    <line x1="110" y1="70" x2="125" y2="55" stroke="#2c3e50" stroke-width="2"/>
                    <circle cx="75" cy="53" r="3" fill="#2c3e50"/>
                    <circle cx="125" cy="53" r="3" fill="#2c3e50"/>
                </svg>`),
            },
            {
                id: 'hope-3',
                label: 'Rainbow',
                svg: () => svgDataUri(`<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200">
                    <rect width="200" height="200" fill="#87ceeb" rx="10"/>
                    <path d="M20 180a80 80 0 01160 0" stroke="#e74c3c" stroke-width="10" fill="none" stroke-linecap="round"/>
                    <path d="M25 180a75 75 0 01150 0" stroke="#f39c12" stroke-width="10" fill="none" stroke-linecap="round"/>
                    <path d="M30 180a70 70 0 01140 0" stroke="#f1c40f" stroke-width="10" fill="none" stroke-linecap="round"/>
                    <path d="M35 180a65 65 0 01130 0" stroke="#2ecc71" stroke-width="10" fill="none" stroke-linecap="round"/>
                    <path d="M40 180a60 60 0 01120 0" stroke="#3498db" stroke-width="10" fill="none" stroke-linecap="round"/>
                    <path d="M45 180a55 55 0 01110 0" stroke="#9b59b6" stroke-width="10" fill="none" stroke-linecap="round"/>
                    <ellipse cx="100" cy="180" rx="60" ry="15" fill="#27ae60"/>
                    <path d="M70 170q15-10 30 0" stroke="#fff" stroke-width="2" fill="none" opacity=".5"/>
                    <path d="M100 170q15-10 30 0" stroke="#fff" stroke-width="2" fill="none" opacity=".5"/>
                </svg>`),
            },
            {
                id: 'hope-4',
                label: 'Shining Star',
                svg: () => svgDataUri(`<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200">
                    <rect width="200" height="200" fill="#1a1a2e" rx="10"/>
                    <circle cx="100" cy="80" r="3" fill="#fff" opacity=".8"/>
                    <circle cx="50" cy="40" r="1.5" fill="#fff" opacity=".5"/>
                    <circle cx="160" cy="30" r="2" fill="#fff" opacity=".6"/>
                    <circle cx="30" cy="130" r="1" fill="#fff" opacity=".4"/>
                    <circle cx="170" cy="150" r="1.5" fill="#fff" opacity=".5"/>
                    <circle cx="70" cy="20" r="1" fill="#fff" opacity=".3"/>
                    <circle cx="140" cy="170" r="1" fill="#fff" opacity=".4"/>
                    <path d="M100 42v20m0-36v-8m16 8l-4 6m-24 22l-4 6m28-6l4 6m-28 10l-4 6m24-32l4-6" stroke="#f1c40f" stroke-width="2" opacity=".6"/>
                    <path d="M100 50l-8-4 5-7-8 0-3-8-3 8-8 0 5 7-8 4 7 3-2 8 6-5 4 7 4-7 6 5-2-8z" fill="#f1c40f"/>
                    <text x="100" y="160" text-anchor="middle" fill="#f1c40f" font-size="16" font-family="sans-serif" opacity=".8">Shine bright</text>
                </svg>`),
            },
        ],
    },
    tenderness: {
        label: 'Tenderness',
        items: [
            {
                id: 'tender-1',
                label: 'Cat',
                svg: () => svgDataUri(`<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200">
                    <ellipse cx="100" cy="110" rx="55" ry="45" fill="#f8c291"/>
                    <circle cx="100" cy="90" r="38" fill="#f8c291"/>
                    <polygon points="70,65 55,35 80,55" fill="#f8c291"/>
                    <polygon points="130,65 145,35 120,55" fill="#f8c291"/>
                    <polygon points="68,63 58,40 78,55" fill="#f19066"/>
                    <polygon points="132,63 142,40 122,55" fill="#f19066"/>
                    <ellipse cx="85" cy="85" rx="5" ry="6" fill="#2c3e50"/>
                    <ellipse cx="115" cy="85" rx="5" ry="6" fill="#2c3e50"/>
                    <ellipse cx="85" cy="83" rx="2" ry="2" fill="#fff"/>
                    <ellipse cx="115" cy="83" rx="2" ry="2" fill="#fff"/>
                    <circle cx="100" cy="100" r="4" fill="#e15f41"/>
                    <path d="M100 105q-6 8 0 12 6-4 0-12" fill="#c44569"/>
                    <path d="M88 95l-6-2m18 2l6-2" stroke="#2c3e50" stroke-width="1.5" opacity=".4"/>
                    <path d="M55 115q-15 5-25 15" stroke="#2c3e50" stroke-width="2" fill="none" opacity=".4"/>
                    <path d="M145 115q15 5 25 15" stroke="#2c3e50" stroke-width="2" fill="none" opacity=".4"/>
                </svg>`),
            },
            {
                id: 'tender-2',
                label: 'Teddy Bear',
                svg: () => svgDataUri(`<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200">
                    <circle cx="70" cy="50" r="20" fill="#c68f5e"/>
                    <circle cx="130" cy="50" r="20" fill="#c68f5e"/>
                    <circle cx="70" cy="45" r="8" fill="#e8b87a"/>
                    <circle cx="130" cy="45" r="8" fill="#e8b87a"/>
                    <circle cx="100" cy="75" r="30" fill="#d4a574"/>
                    <circle cx="100" cy="85" r="35" fill="#c68f5e"/>
                    <ellipse cx="100" cy="130" rx="50" ry="55" fill="#d4a574"/>
                    <ellipse cx="100" cy="150" rx="40" ry="30" fill="#c68f5e"/>
                    <ellipse cx="75" cy="120" rx="20" ry="35" fill="#d4a574" transform="rotate(25 75 120)"/>
                    <ellipse cx="125" cy="120" rx="20" ry="35" fill="#d4a574" transform="rotate(-25 125 120)"/>
                    <circle cx="88" cy="78" r="4" fill="#2c3e50"/>
                    <circle cx="112" cy="78" r="4" fill="#2c3e50"/>
                    <ellipse cx="88" cy="76" rx="1.5" ry="1.5" fill="#fff"/>
                    <ellipse cx="112" cy="76" rx="1.5" ry="1.5" fill="#fff"/>
                    <circle cx="100" cy="88" r="5" fill="#2c3e50"/>
                    <ellipse cx="100" cy="95" rx="5" ry="3" fill="#2c3e50"/>
                </svg>`),
            },
            {
                id: 'tender-3',
                label: 'Hug',
                svg: () => svgDataUri(`<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200">
                    <defs><linearGradient id="hp" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#ff9ff3"/><stop offset="100%" style="stop-color:#f368e0"/>
                    </linearGradient></defs>
                    <circle cx="80" cy="70" r="20" fill="#ffda79"/>
                    <circle cx="120" cy="70" r="20" fill="#ffda79"/>
                    <path d="M70 115c-12 0-20 12-25 25-2 5 2 10 8 10h44c6 0 10-5 8-10-5-13-13-25-25-25z" fill="#f19066"/>
                    <path d="M130 115c-12 0-20 12-25 25-2 5 2 10 8 10h44c6 0 10-5 8-10-5-13-13-25-25-25z" fill="#f19066"/>
                    <path d="M90 100q10-15 20 0" stroke="#ff6b6b" stroke-width="2" fill="none" stroke-linecap="round"/>
                    <path d="M50 90q-30 20-15 50" stroke="url(#hp)" stroke-width="15" fill="none" stroke-linecap="round" opacity=".6"/>
                    <path d="M150 90q30 20 15 50" stroke="url(#hp)" stroke-width="15" fill="none" stroke-linecap="round" opacity=".6"/>
                    <path d="M55 85l6 6m6-10l4 8" stroke="#ff6b6b" stroke-width="2" opacity=".5"/>
                    <path d="M145 85l-6 6m-6-10l-4 8" stroke="#ff6b6b" stroke-width="2" opacity=".5"/>
                </svg>`),
            },
            {
                id: 'tender-4',
                label: 'Bunny',
                svg: () => svgDataUri(`<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200">
                    <ellipse cx="100" cy="120" rx="50" ry="45" fill="#fff" opacity=".95"/>
                    <ellipse cx="100" cy="80" rx="35" ry="30" fill="#fff" opacity=".95"/>
                    <ellipse cx="65" cy="45" rx="12" ry="28" fill="#fff" transform="rotate(-15 65 45)" opacity=".9"/>
                    <ellipse cx="62" cy="48" rx="6" ry="18" fill="#ffd1dc" transform="rotate(-15 62 48)" opacity=".6"/>
                    <ellipse cx="135" cy="45" rx="12" ry="28" fill="#fff" transform="rotate(15 135 45)" opacity=".9"/>
                    <ellipse cx="138" cy="48" rx="6" ry="18" fill="#ffd1dc" transform="rotate(15 138 48)" opacity=".6"/>
                    <circle cx="88" cy="75" r="4" fill="#2c3e50"/>
                    <circle cx="112" cy="75" r="4" fill="#2c3e50"/>
                    <circle cx="89" cy="73" r="1.5" fill="#fff"/>
                    <circle cx="113" cy="73" r="1.5" fill="#fff"/>
                    <ellipse cx="100" cy="82" rx="3" ry="2" fill="#ff6b6b"/>
                    <path d="M95 87q5 5 10 0" stroke="#2c3e50" stroke-width="1.5" fill="none" stroke-linecap="round"/>
                    <path d="M82 90q-6 4-12 2m30 0q-4 4 2 8" stroke="#2c3e50" stroke-width="1.5" fill="none" opacity=".3"/>
                    <circle cx="100" cy="145" r="15" fill="#fff" opacity=".7"/>
                </svg>`),
            },
        ],
    },
    gratitude: {
        label: 'Gratitude',
        items: [
            {
                id: 'grat-1',
                label: 'Hands & Heart',
                svg: () => svgDataUri(`<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200">
                    <defs><linearGradient id="hh" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#ff6b6b"/><stop offset="100%" style="stop-color:#c0392b"/>
                    </linearGradient></defs>
                    <path d="M45 140c-8-10-12-25-5-35 8-12 25-8 28-4-3-12 2-28 15-30 15-2 20 12 18 24" stroke="#f19066" stroke-width="10" fill="none" stroke-linecap="round"/>
                    <path d="M155 140c8-10 12-25 5-35-8-12-25-8-28-4 3-12-2-28-15-30-15-2-20 12-18 24" stroke="#f19066" stroke-width="10" fill="none" stroke-linecap="round"/>
                    <path d="M100 175c-20-18-30-30-30-46 0-12 8-22 20-22 7 0 10 3 10 3s3-3 10-3c12 0 20 10 20 22 0 16-10 28-30 46z" fill="url(#hh)"/>
                    <circle cx="92" cy="135" r="2" fill="#fff" opacity=".4"/>
                    <circle cx="108" cy="130" r="1.5" fill="#fff" opacity=".3"/>
                </svg>`),
            },
            {
                id: 'grat-2',
                label: 'Prayer',
                svg: () => svgDataUri(`<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200">
                    <defs><linearGradient id="pg" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#f1c40f"/><stop offset="100%" style="stop-color:#f39c12"/>
                    </linearGradient></defs>
                    <circle cx="100" cy="55" r="22" fill="#ffda79"/>
                    <path d="M65 95l20 10 15-10 15 10 20-10" fill="none" stroke="#f19066" stroke-width="8" stroke-linecap="round"/>
                    <path d="M65 95c-12 30-8 60 35 75" fill="none" stroke="#f19066" stroke-width="8" stroke-linecap="round"/>
                    <path d="M135 95c12 30 8 60-35 75" fill="none" stroke="#f19066" stroke-width="8" stroke-linecap="round"/>
                    <circle cx="100" cy="155" r="15" fill="url(#pg)" opacity=".3"/>
                    <path d="M95 150l5-10 5 10" fill="none" stroke="url(#pg)" stroke-width="2" stroke-linecap="round"/>
                    <circle cx="100" cy="145" r="20" fill="url(#pg)" opacity=".15"/>
                    <ellipse cx="90" cy="50" rx="2" ry="3" fill="#2c3e50"/>
                    <ellipse cx="110" cy="50" rx="2" ry="3" fill="#2c3e50"/>
                </svg>`),
            },
            {
                id: 'grat-3',
                label: 'Gift Box',
                svg: () => svgDataUri(`<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200">
                    <rect x="45" y="80" width="110" height="90" rx="5" fill="#e74c3c"/>
                    <rect x="45" y="80" width="110" height="20" rx="3" fill="#c0392b"/>
                    <rect x="90" y="80" width="20" height="90" fill="#c0392b" opacity=".5"/>
                    <path d="M100 65q-20-25-45-15-5 2-8 7" fill="none" stroke="#f1c40f" stroke-width="6" stroke-linecap="round"/>
                    <path d="M100 65q20-25 45-15 5 2 8 7" fill="none" stroke="#f1c40f" stroke-width="6" stroke-linecap="round"/>
                    <ellipse cx="100" cy="52" rx="25" ry="12" fill="none" stroke="#f1c40f" stroke-width="4"/>
                    <circle cx="100" cy="52" r="6" fill="#f1c40f"/>
                    <path d="M70 110h60m-60 20h50m-40 20h30" stroke="#fff" stroke-width="2" opacity=".3" stroke-linecap="round"/>
                </svg>`),
            },
            {
                id: 'grat-4',
                label: 'Thank You Ribbon',
                svg: () => svgDataUri(`<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200">
                    <defs><linearGradient id="rb" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#3498db"/><stop offset="100%" style="stop-color:#9b59b6"/>
                    </linearGradient></defs>
                    <rect x="20" y="100" width="160" height="50" rx="10" fill="url(#rb)"/>
                    <rect x="20" y="100" width="160" height="50" rx="10" fill="none" stroke="#fff" stroke-width="2" opacity=".3"/>
                    <text x="100" y="133" text-anchor="middle" fill="#fff" font-size="24" font-family="Georgia, serif" font-weight="bold">Thank You</text>
                    <path d="M12 100l-8 25 8 25" stroke="url(#rb)" stroke-width="6" fill="none" stroke-linecap="round"/>
                    <path d="M188 100l8 25-8 25" stroke="url(#rb)" stroke-width="6" fill="none" stroke-linecap="round"/>
                    <circle cx="8" cy="100" r="6" fill="#3498db"/>
                    <circle cx="192" cy="100" r="6" fill="#9b59b6"/>
                    <circle cx="8" cy="150" r="6" fill="#9b59b6"/>
                    <circle cx="192" cy="150" r="6" fill="#3498db"/>
                    <path d="M30 88l-6 12m-6-6l12 6" stroke="#f1c40f" stroke-width="2" opacity=".6"/>
                    <path d="M170 162l6-12m6 6l-12-6" stroke="#f1c40f" stroke-width="2" opacity=".6"/>
                </svg>`),
            },
        ],
    },
}

const currentCategory = computed(() => IMAGE_CATEGORIES[imgCategory.value])
const categoryItems = computed(() => currentCategory.value?.items ?? [])
</script>

<template>
    <div class="flex w-64 flex-col border-l bg-white">
        <div class="flex border-b">
            <button
                v-for="t in ['images', 'shapes', 'phrases', 'qr', 'backgrounds']"
                :key="t"
                class="flex-1 px-2 py-2.5 text-xs font-semibold uppercase tracking-wider transition"
                :class="tab === t
                    ? 'border-b-2 border-indigo-500 text-indigo-600'
                    : 'text-gray-400 hover:text-gray-600'"
                @click="tab = t"
            >
                {{ t === 'images' ? 'Img' : t === 'shapes' ? 'Shapes' : t === 'phrases' ? 'Text' : t === 'qr' ? 'QR' : 'Bg' }}
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-3">
            <div v-if="tab === 'images'" key="images-tab">
                <div class="mb-3 flex gap-1">
                    <button
                        v-for="(cat, key) in IMAGE_CATEGORIES"
                        :key="key"
                        class="flex-1 rounded px-2 py-1 text-xs font-medium transition"
                        :class="imgCategory === key
                            ? 'bg-rose-100 text-rose-700'
                            : 'text-gray-500 hover:bg-gray-100'"
                        @click="imgCategory = key"
                    >
                        {{ cat.label }}
                    </button>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <button
                        v-for="item in categoryItems"
                        :key="item.id"
                        class="group relative overflow-hidden rounded-lg border-2 border-transparent transition hover:border-rose-400 active:scale-95"
                        @click="$emit('addImageToCanvas', item.svg())"
                        :title="'Add ' + item.label"
                    >
                        <img
                            :src="item.svg()"
                            :alt="item.label"
                            class="h-24 w-full object-cover"
                        />
                    </button>
                </div>
            </div>

            <div v-if="tab === 'shapes'" class="grid grid-cols-3 gap-2">
                <button
                    v-for="s in shapes"
                    :key="s"
                    class="flex flex-col items-center gap-1 rounded-lg border-2 border-transparent p-3 transition hover:border-pink-400 hover:bg-pink-50 active:scale-95"
                    @click="$emit('addShape', s)"
                    :title="'Add ' + s"
                >
                    <svg class="h-7 w-7 text-pink-500" fill="currentColor" viewBox="0 0 24 24">
                        <path :d="SHAPE_ICONS[s]" />
                    </svg>
                    <span class="text-xs capitalize text-gray-500">{{ s }}</span>
                </button>
            </div>

            <div v-if="tab === 'phrases'" class="space-y-2">
                <button
                    v-for="(phrase, i) in phrases"
                    :key="i"
                    class="w-full rounded-lg border-2 border-transparent px-4 py-3 text-left text-sm transition hover:border-rose-300 hover:bg-rose-50 active:scale-95"
                    @click="$emit('addText', phrase)"
                >
                    <span class="text-gray-700">{{ phrase }}</span>
                </button>
            </div>

            <div v-if="tab === 'qr'" class="space-y-3">
                <p class="text-xs text-gray-400">
                    QR linking to: <code class="text-indigo-600 break-all">{{ window.location.origin }}/page?uuid={{ pageUuid }}</code>
                </p>
                <div>
                    <label class="mb-1 block text-xs text-gray-400">QR Color</label>
                    <input v-model="qrForeground" type="color" class="h-8 w-full cursor-pointer rounded border" />
                </div>
                <div>
                    <label class="mb-1 block text-xs text-gray-400">Background Color</label>
                    <input v-model="qrBackground" type="color" class="h-8 w-full cursor-pointer rounded border" />
                </div>
                <button
                    class="w-full rounded-lg bg-violet-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-violet-600 active:scale-95"
                    @click="$emit('addQr', {
                        text: window.location.origin + '/page?uuid=' + pageUuid,
                        foreground_color: qrForeground,
                        background_color: qrBackground,
                        error_correction_level: 'medium',
                    })"
                >
                    Add QR to Canvas
                </button>
            </div>

            <div v-if="tab === 'backgrounds'">
                <div class="mb-3 flex gap-1">
                    <button
                        v-for="st in ['solids', 'pastels', 'gradients']"
                        :key="st"
                        class="flex-1 rounded px-2 py-1 text-xs font-medium transition"
                        :class="bgSubtab === st
                            ? 'bg-indigo-100 text-indigo-700'
                            : 'text-gray-500 hover:bg-gray-100'"
                        @click="bgSubtab = st"
                    >
                        {{ st === 'solids' ? 'Solid' : st === 'pastels' ? 'Pastel' : 'Gradient' }}
                    </button>
                </div>

                <div v-if="bgSubtab === 'solids'" class="grid grid-cols-5 gap-1.5">
                    <button
                        v-for="c in ['#ef4444','#f97316','#eab308','#22c55e','#06b6d4','#3b82f6','#8b5cf6','#ec4899','#64748b','#1e293b','#dc2626','#ea580c','#ca8a04','#16a34a','#0891b2','#2563eb','#7c3aed','#db2777','#475569','#0f172a']"
                        :key="c"
                        class="h-8 w-full rounded-lg border border-gray-200 transition hover:scale-110 active:scale-95"
                        :style="{ backgroundColor: c }"
                        @click="$emit('setBackground', c)"
                        :title="c"
                    ></button>
                </div>

                <div v-if="bgSubtab === 'pastels'" class="grid grid-cols-5 gap-1.5">
                    <button
                        v-for="c in ['#fce4ec','#f3e5f5','#e8eaf6','#e3f2fd','#e0f7fa','#e0f2f1','#e8f5e9','#fff9c4','#fff3e0','#fbe9e7','#fce4ec','#f1f8e9','#fff8e1','#e1f5fe','#f3e5f5']"
                        :key="c"
                        class="h-8 w-full rounded-lg border border-gray-200 transition hover:scale-110 active:scale-95"
                        :style="{ backgroundColor: c }"
                        @click="$emit('setBackground', c)"
                        :title="c"
                    ></button>
                </div>

                <div v-if="bgSubtab === 'gradients'" class="space-y-2">
                    <button
                        v-for="g in [{l:'Purple Blue',v:'linear-gradient(135deg, #667eea 0%, #764ba2 100%)'},{l:'Pink Red',v:'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)'},{l:'Blue Cyan',v:'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)'},{l:'Green Teal',v:'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)'},{l:'Pink Yellow',v:'linear-gradient(135deg, #fa709a 0%, #fee140 100%)'},{l:'Lavender',v:'linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%)'},{l:'Peach',v:'linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%)'},{l:'Sky Blue',v:'linear-gradient(135deg, #89f7fe 0%, #66a6ff 100%)'},{l:'Cream Sky',v:'linear-gradient(135deg, #fddb92 0%, #d1fdff 100%)'},{l:'Silver',v:'linear-gradient(135deg, #c3cfe2 0%, #f5f7fa 100%)'},{l:'Sunset',v:'linear-gradient(135deg, #fad0c4 0%, #ffd1ff 100%)'},{l:'Ocean',v:'linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%)'}]"
                        :key="g.l"
                        class="h-12 w-full rounded-lg border border-gray-200 transition hover:scale-[1.02] active:scale-95 flex items-center justify-center"
                        :style="{ background: g.v }"
                        @click="$emit('setBackground', g.v)"
                        :title="g.l"
                    >
                        <span class="text-xs font-medium drop-shadow-md"
                            :class="g.l === 'Cream Sky' || g.l === 'Silver' ? 'text-gray-700' : 'text-white'"
                        >{{ g.l }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
