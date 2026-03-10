const productData = {
  "GLYCOLS": ["Butyl carbitol", "Butyl glycol", "Di-ethylene glycol", "Mono ethylene glycol", "Propylene glycol", "Tri-ethylene glycol"],
  "MONOMERS": ["Butyl Acrylate", "2-Ethylhexyl acrylate", "Ethyl acrylate", "Glacial Acrylic Acid", "Methyl acrylate", "Methacrylic acid", "Styrene monomer", "Vinyl acetate monomer"],
  "SOLVENTS": ["Acetone", "Chloroform", "Cyclohexane", "Ethylene dichloride (EDC)", "Ethyl acetate", "Methyl ethyl ketone", "Mix xylene", "MTO", "Methylene dichloride (MDC)", "Spent solvent", "Solvent crude"],
  "ACRYLATES": ["Acetic Acid", "Caustic soda lye", "Di ethanolamine", "Formaldehyde", "Glycerin", "Maleic anhydride", "Phenol", "Melamine"],
  "AROMATICS": ["C-9 Solvent", "C-10 Solvent", "Toluene", "Xylene"],
  "OXO": ["Iso Butanol", "Isopropyl Alcohol (IPA)", "Methanol", "n-Butanol", "Polyvinyl alcohol", "2-Ethyl hexanol"]
};

const productInfo = {
  "Butyl carbitol": {
    desc: "Serves as a coupling agent and solvent in household/industrial cleaners, and as a primary solvent in silk screen printing inks.",
    specs: { "Molecular Weight": "162.2 g/mol", "Boiling Point": "230 °C (446 °F)", "Flash Point": "114 °C (Closed Cup)", "Freezing Point": "-68 °C" }
  },
  "Butyl glycol": {
    desc: "Primarily used in the paint industry to extend drying times/improve flow. Also acts as a solvent in printing inks and textile dyes.",
    specs: { "Appearance": "Clear and Bright", "Applications": "Paints, Inks, Cosmetics", "Grade": "Industrial" }
  },
  "Di-ethylene glycol": {
    desc: "Features high hygroscopicity, low volatility, and freezing point depression. Used in gas dehydration, plasticizers, and lubricants.",
    specs: { "Density @ 20°C": "1.1184 Kg/l", "Initial Boiling Point": "245 °C", "Water Content": "Max 0.03%", "Acidity": "Max 0.0004%" }
  }
};

let currentCategory = null;

// ═══════════════════════════════════════════════════════
//  THREE.JS — PHYSICAL FACILITY WORLD
// ═══════════════════════════════════════════════════════
const cv = document.getElementById('world-canvas');
const renderer = new THREE.WebGLRenderer({ canvas: cv, antialias: true });
renderer.setPixelRatio(Math.min(devicePixelRatio, 2));
renderer.shadowMap.enabled = true;
renderer.shadowMap.type = THREE.PCFSoftShadowMap;
renderer.toneMapping = THREE.ACESFilmicToneMapping;
renderer.toneMappingExposure = 1.05;

const scene = new THREE.Scene();
scene.background = new THREE.Color(0xe9e4dc);
scene.fog = new THREE.FogExp2(0xe5e0d7, 0.032);

const camera = new THREE.PerspectiveCamera(58, innerWidth / innerHeight, 0.1, 300);
camera.position.set(0, 2.0, 9);

function onResize() {
  renderer.setSize(innerWidth, innerHeight);
  camera.aspect = innerWidth / innerHeight;
  camera.updateProjectionMatrix();
}
onResize();
window.addEventListener('resize', onResize);

// ── LIGHTING ──
const ambient = new THREE.AmbientLight(0xfff8f0, 1.0);
scene.add(ambient);
const sun = new THREE.DirectionalLight(0xfff5e8, 2.0);
sun.position.set(15, 25, 10);
sun.castShadow = true;
sun.shadow.mapSize.set(2048, 2048);
sun.shadow.camera.near = 0.5;
sun.shadow.camera.far = 200;
sun.shadow.camera.left = sun.shadow.camera.bottom = -40;
sun.shadow.camera.right = sun.shadow.camera.top = 40;
sun.shadow.bias = -0.003;
scene.add(sun);
scene.add(new THREE.DirectionalLight(0xd0e8f8, 0.45).translateX(-10).translateY(6).translateZ(-5));
scene.add(new THREE.HemisphereLight(0xfff0e0, 0xc0b8a8, 0.5));

// ── MATERIALS ──
const M = {
  floor: new THREE.MeshLambertMaterial({ color: 0xd5d0c8 }),
  wall: new THREE.MeshLambertMaterial({ color: 0xe2ddd5 }),
  ceil: new THREE.MeshLambertMaterial({ color: 0xeae6e0 }),
  steel: new THREE.MeshPhongMaterial({ color: 0xb8b4ac, specular: 0x888070, shininess: 50 }),
  green: new THREE.MeshPhongMaterial({ color: 0x3a6050, specular: 0x1a3028, shininess: 30 }),
  drum: new THREE.MeshPhongMaterial({ color: 0xaaa49c, specular: 0x605c54, shininess: 30 }),
  drum2: new THREE.MeshPhongMaterial({ color: 0x98a0a8, specular: 0x4a5058, shininess: 30 }),
  drum3: new THREE.MeshPhongMaterial({ color: 0xa8a898, specular: 0x505048, shininess: 25 }),
  tank: new THREE.MeshPhongMaterial({ color: 0xa8c0b0, specular: 0x607860, shininess: 60 }),
  glass: new THREE.MeshPhongMaterial({ color: 0xc8e0e8, specular: 0xffffff, shininess: 150, transparent: true, opacity: 0.3 }),
  pipe: new THREE.MeshPhongMaterial({ color: 0x909088, specular: 0x606060, shininess: 55 }),
  rack: new THREE.MeshLambertMaterial({ color: 0x888278 }),
  sign: new THREE.MeshLambertMaterial({ color: 0x2c4a3e }),
  beam: new THREE.MeshLambertMaterial({ color: 0x9c9890 }),
  yellow: new THREE.MeshPhongMaterial({ color: 0xd4a830, specular: 0x806020, shininess: 40 }),
  red: new THREE.MeshPhongMaterial({ color: 0xb04030, specular: 0x602018, shininess: 30 }),
};

// ── HELPERS ──
const add = obj => (scene.add(obj), obj);
function B(w, h, d, mat) {
  const m = new THREE.Mesh(new THREE.BoxGeometry(w, h, d), mat);
  m.castShadow = m.receiveShadow = true;
  return m;
}
function C(rt, rb, h, mat, segs = 16) {
  const m = new THREE.Mesh(new THREE.CylinderGeometry(rt, rb, h, segs), mat);
  m.castShadow = m.receiveShadow = true;
  return m;
}
function S(r, mat, segs = 14) {
  const m = new THREE.Mesh(new THREE.SphereGeometry(r, segs, 10), mat);
  m.castShadow = true;
  return m;
}
function place(mesh, x, y, z, rx = 0, ry = 0, rz = 0) {
  mesh.position.set(x, y, z);
  mesh.rotation.set(rx, ry, rz);
  return add(mesh);
}

// ─────────────────────────────────────────────────────
//  CORRIDOR INFRASTRUCTURE (runs z: +10 to -180)
// ─────────────────────────────────────────────────────
const FLOOR_LEN = 250;
const FLOOR_W = 22;

// Ground
const floorM = new THREE.Mesh(new THREE.PlaneGeometry(FLOOR_W, FLOOR_LEN), M.floor);
floorM.rotation.x = -Math.PI / 2;
floorM.position.set(0, 0, -FLOOR_LEN / 2 + 10);
floorM.receiveShadow = true;
scene.add(floorM);

// Grid on floor
const grid = new THREE.GridHelper(FLOOR_LEN, FLOOR_LEN / 2, 0xbbb5ac, 0xcac4bc);
grid.position.set(0, 0.005, -FLOOR_LEN / 2 + 10);
grid.material.opacity = 0.35;
grid.material.transparent = true;
scene.add(grid);

// Ceiling
const ceilM = new THREE.Mesh(new THREE.PlaneGeometry(FLOOR_W + 2, FLOOR_LEN), M.ceil);
ceilM.rotation.x = Math.PI / 2;
ceilM.position.set(0, 7.5, -FLOOR_LEN / 2 + 10);
ceilM.receiveShadow = true;
scene.add(ceilM);

// Left & right walls
const wallL = new THREE.Mesh(new THREE.PlaneGeometry(FLOOR_LEN, 7.5), M.wall);
wallL.rotation.y = Math.PI / 2; wallL.position.set(-FLOOR_W / 2, 3.75, -FLOOR_LEN / 2 + 10);
wallL.receiveShadow = true; scene.add(wallL);
const wallR = new THREE.Mesh(new THREE.PlaneGeometry(FLOOR_LEN, 7.5), M.wall);
wallR.rotation.y = -Math.PI / 2; wallR.position.set(FLOOR_W / 2, 3.75, -FLOOR_LEN / 2 + 10);
wallR.receiveShadow = true; scene.add(wallR);

// Structural columns + cross beams every 10m
for (let z = 8; z > -180; z -= 10) {
  place(B(0.5, 7.5, 0.5, M.beam), -10, 3.75, z);
  place(B(0.5, 7.5, 0.5, M.beam), 10, 3.75, z);
  place(B(20.5, 0.35, 0.5, M.beam), 0, 7.25, z);
  // Ceiling light strip
  const ls = new THREE.Mesh(new THREE.BoxGeometry(5, 0.06, 0.25), new THREE.MeshBasicMaterial({ color: 0xfff8ec }));
  ls.position.set(0, 7.2, z); scene.add(ls);
  // Point light every 20m
  if (z % 20 === 8 % 20) {
    const pl = new THREE.PointLight(0xfff5e0, 0.5, 22);
    pl.position.set(0, 6.8, z); scene.add(pl);
  }
}

// ─────────────────────────────────────────────────────
//  SCENE 0 — ENTRANCE (z: +5 to -15)
// ─────────────────────────────────────────────────────
// Reception desk
const desk = new THREE.Group();
desk.add(place(B(4.5, 1.0, 1.4, M.steel), 0, 0, 0));
desk.add(place(B(4.3, 0.05, 1.2, M.glass), 0, 0.5, 0));
desk.position.set(-4, 0.5, -2); scene.add(desk);

// Company sign on left wall — logo texture
{
  const signBacking = B(0.04, 1.5, 4.0, new THREE.MeshLambertMaterial({ color: 0xffffff }));
  signBacking.position.set(-9.97, 4.5, -5);
  signBacking.rotation.y = Math.PI / 2;
  scene.add(signBacking);

  const logoTex = new THREE.TextureLoader().load('logo.jpg', tex => {
    tex.colorSpace = THREE.SRGBColorSpace;
  });
  const logoMat = new THREE.MeshBasicMaterial({ map: logoTex, transparent: true });
  const logoPlane = new THREE.Mesh(new THREE.PlaneGeometry(3.5, 1.2), logoMat);
  logoPlane.position.set(-9.94, 4.5, -5);
  logoPlane.rotation.y = Math.PI / 2;
  scene.add(logoPlane);
}

// Entrance planters
const plantMat = new THREE.MeshLambertMaterial({ color: 0x5c8870 });
for (let s of [-1, 1]) {
  const pot = C(0.35, 0.4, 0.65, M.drum); pot.position.set(s * 8, 0.32, 0); scene.add(pot);
  const plant = S(0.55, plantMat); plant.position.set(s * 8, 0.95, 0); scene.add(plant);
  const plant2 = S(0.4, plantMat); plant2.position.set(s * 8 + 0.3, 0.85, 0.2); scene.add(plant2);
}
// Safety signs on right wall
for (let z of [-3, -8]) {
  place(B(0.04, 1.2, 0.85, M.sign), 9.97, 2.8, z, 0, -Math.PI / 2);
}

// ─────────────────────────────────────────────────────
//  SCENE 1 — LABORATORY (z: -15 to -40)
// ─────────────────────────────────────────────────────
// Left bench
place(B(9, 1.0, 1.6, M.steel), -5.5, 0.5, -25);
place(B(8.8, 0.05, 1.4, M.glass), -5.5, 1.02, -25);

// Flask arrangement on bench
const flaskPositions = [-3, -2, -1, 0, 1, 2, 3, 4];
flaskPositions.forEach((xo, i) => {
  const h = 0.35 + Math.random() * 0.25;
  const f = C(0.1 + Math.random() * 0.06, 0.14 + Math.random() * 0.06, h, M.glass, 10);
  f.position.set(-5.5 + xo * 0.7 + 0.2, 1.02 + h / 2, -25.4);
  scene.add(f);
  const fs = S(0.12 + Math.random() * 0.04, M.glass);
  fs.position.set(-5.5 + xo * 0.7 + 0.2, 1.02 + h, -25.4);
  scene.add(fs);
});

// Right side bench
place(B(7, 1.0, 1.6, M.steel), 5.5, 0.5, -30);
place(B(6.8, 0.05, 1.4, M.glass), 5.5, 1.02, -30);
for (let i = 0; i < 4; i++) {
  const f2 = C(0.12, 0.16, 0.4, M.glass, 10);
  f2.position.set(3.5 + i * 0.8, 1.22, -30.4); scene.add(f2);
}

// Overhead storage shelves
place(B(9, 0.12, 0.9, M.rack), -5.5, 3.5, -22);
place(B(9, 0.12, 0.9, M.rack), -5.5, 4.8, -22);
place(B(7, 0.12, 0.9, M.rack), 5.5, 3.5, -30);

// Lab drums (left)
for (let i = 0; i < 3; i++) {
  const dm = [M.drum, M.drum2, M.drum3][i];
  place(C(0.4, 0.42, 0.85, dm), -9 + i * 0.85, 0.42, -18);
}
// Stack drums
place(C(0.4, 0.42, 0.85, M.drum2), -9, 1.27, -18);

// Vertical pipes on left wall
for (let z of [-20, -25, -32, -38]) {
  place(C(0.07, 0.07, 7.5, M.pipe, 8), -9.7, 3.75, z);
}
// Horizontal pipe run
const hpipe = C(0.08, 0.08, 18, M.pipe, 8);
hpipe.rotation.z = Math.PI / 2;
hpipe.position.set(-9.7, 5.5, -26); scene.add(hpipe);

// ─────────────────────────────────────────────────────
//  SCENE 2 — PRODUCTS / RACK STORAGE (z: -40 to -80)
// ─────────────────────────────────────────────────────
const drumColors = [0xaaa49c, 0x98a0a8, 0xa8a898, 0xb0a090, 0x90a890, 0x9890a0];
for (let side of [-1, 1]) {
  for (let iz = 0; iz < 5; iz++) {
    const rz = -44 - iz * 7;
    const rx = side * 8.2;
    // Rack posts
    for (let post of [-2.2, 2.2]) {
      place(B(0.18, 6.5, 0.18, M.rack), rx + post, 3.25, rz);
    }
    // Rack back
    place(B(0.08, 6.5, 1.1, M.rack), rx, 3.25, rz - 0.6);
    // Shelves x4
    for (let s = 0; s < 4; s++) {
      const sy = 0.6 + s * 1.55;
      place(B(4.7, 0.1, 1.0, M.rack), rx, sy, rz);
      // Drums on each shelf
      for (let d = 0; d < 3; d++) {
        const dmat = new THREE.MeshPhongMaterial({ color: drumColors[(iz + d + s) % drumColors.length], specular: 0x505050, shininess: 25 });
        const drum = C(0.28, 0.3, 0.68, dmat, 12);
        drum.position.set(rx - 1.1 + d * 0.75, sy + 0.41, rz); scene.add(drum);
      }
    }
  }
}
// Walkway arrows painted on floor
for (let z = -42; z > -78; z -= 8) {
  const arrow = new THREE.Mesh(new THREE.PlaneGeometry(0.4, 1.0), new THREE.MeshLambertMaterial({ color: 0xb8a878 }));
  arrow.rotation.x = -Math.PI / 2; arrow.position.set(0, 0.01, z); scene.add(arrow);
}

// ─────────────────────────────────────────────────────
//  SCENE 3 — WAREHOUSE / TANKS (z: -80 to -120)
// ─────────────────────────────────────────────────────
// Large cylindrical storage tanks LEFT
for (let i = 0; i < 3; i++) {
  const tz = -86 - i * 9;
  const tank = C(2.2, 2.2, 5, M.tank, 24); tank.position.set(-5.5, 2.5, tz); scene.add(tank);
  const tcap = S(2.22, M.tank, 16); tcap.position.set(-5.5, 5, tz); scene.add(tcap);
  // Pipe connections
  const tp = C(0.1, 0.1, 4, M.pipe, 8); tp.rotation.z = Math.PI / 2; tp.position.set(-3.2, 1.2, tz); scene.add(tp);
  // Ladder rungs
  for (let r = 0; r < 5; r++) {
    const rung = B(0.6, 0.06, 0.06, M.steel); rung.position.set(-5.5, 0.5 + r * 0.7, tz + 2.22); scene.add(rung);
  }
}
// Smaller tanks RIGHT
for (let i = 0; i < 4; i++) {
  const tz = -82 - i * 7;
  const t2 = C(1.4, 1.4, 3.8, M.tank, 20); t2.position.set(6, 1.9, tz); scene.add(t2);
  const t2c = S(1.42, M.tank, 14); t2c.position.set(6, 3.8, tz); scene.add(t2c);
}
// Pipe network along ceiling
for (let z = -82; z > -118; z -= 4) {
  const pv = C(0.08, 0.08, 7.4, M.pipe, 8); pv.position.set(-9.6, 3.75, z); scene.add(pv);
  if (z % 8 === (-82) % 8) {
    const ph = C(0.08, 0.08, 4, M.pipe, 8); ph.rotation.z = Math.PI / 2; ph.position.set(-7.6, 5.8, z); scene.add(ph);
  }
}
// Loading dock end
place(B(16, 0.2, 7, M.floor), 0, 0.08, -112);
place(B(10, 2.5, 0.4, M.steel), 0, 1.25, -115.5);
// Pallets with crates
for (let i = 0; i < 4; i++) {
  const px = -4.5 + i * 3;
  place(B(2.0, 0.12, 1.4, M.rack), px, 0.06, -109);
  place(B(1.8, 0.9, 1.2, M.drum), px, 0.57, -109);
}

// ─────────────────────────────────────────────────────
//  SCENE 4 — DISPATCH / TRUCKS (z: -120 to -155)
// ─────────────────────────────────────────────────────
for (let i = 0; i < 2; i++) {
  const tx2 = i === 0 ? -5 : 5;
  const tz2 = -126 - i * 14;
  // Cab
  const cabG = new THREE.Group();
  place(B(3, 2.4, 2.5, M.steel), 0, 1.2, 0);
  cabG.add(B(3, 2.4, 2.5, M.steel)); cabG.children[0].position.set(0, 1.2, 0);
  // Windshield
  const wsMat = new THREE.MeshPhongMaterial({ color: 0xc0d4dc, specular: 0xffffff, shininess: 120, transparent: true, opacity: 0.5 });
  cabG.add(place(B(2.8, 1.2, 0.06, wsMat), 0, 1.8, 1.28));
  // Trailer
  cabG.add(place(B(2.8, 3.0, 8, M.rack), 0, 1.5, 5.5));
  // Wheels
  for (let w = 0; w < 3; w++) {
    const wz = -1 + w * 3;
    cabG.add(place(C(0.45, 0.45, 0.22, M.drum, 16), -1.55, 0.45, wz, 0, 0, Math.PI / 2));
    cabG.add(place(C(0.45, 0.45, 0.22, M.drum, 16), 1.55, 0.45, wz, 0, 0, Math.PI / 2));
  }
  cabG.position.set(tx2, 0, tz2); scene.add(cabG);
}
// India map painted on back wall section
place(B(0.04, 4, 6, M.sign), -9.97, 4, -135, 0, Math.PI / 2);

// ─────────────────────────────────────────────────────
//  SCENE 5 — END WALL / CONTACT (z: -155 to -170)
// ─────────────────────────────────────────────────────
place(B(22, 8, 0.5, M.wall), 0, 4, -162);
place(B(8, 0.08, 3, M.sign), 0, 5.5, -161.7);
// Decorative end columns
for (let x of [-8, -4, 0, 4, 8]) {
  place(C(0.12, 0.12, 7.5, M.pipe, 8), x, 3.75, -157);
}
// Plants
const pGreen = new THREE.MeshLambertMaterial({ color: 0x4a7060 });
for (let s of [-1, 1]) {
  place(C(0.4, 0.45, 0.7, M.drum, 12), s * 6, 0.35, -160);
  place(S(0.65, pGreen, 12), s * 6, 0.95, -160);
  place(S(0.45, pGreen, 10), s * 6 + 0.4, 0.8, -159.5);
}

// ─────────────────────────────────────────────────────
//  FLOATING MOLECULES throughout corridor
// ─────────────────────────────────────────────────────
const molGroup = new THREE.Group();
scene.add(molGroup);

const amat = new THREE.MeshPhongMaterial({ color: 0x4a7a5e, specular: 0x304a3a, shininess: 60 });
const bmat = new THREE.MeshPhongMaterial({ color: 0x7a9e90, specular: 0x405848, shininess: 30 });
const molData = [];

function makeMol(x, y, z, scale = 1) {
  const g = new THREE.Group();
  const pts = [[0, 0, 0], [.9, .5, 0], [-.7, .6, .2], [.1, -.8, .3], [.9, 1.2, .1], [1.5, .1, .1]];
  pts.forEach(([px, py, pz]) => {
    const a = new THREE.Mesh(new THREE.SphereGeometry(0.13 * scale, 8, 6), amat.clone());
    a.position.set(px * scale, py * scale, pz * scale); a.castShadow = true; g.add(a);
  });
  // Bond sticks
  [[0, 1], [0, 2], [0, 3], [1, 4], [1, 5]].forEach(([ai, bi]) => {
    const p0 = new THREE.Vector3(...pts[ai]).multiplyScalar(scale);
    const p1 = new THREE.Vector3(...pts[bi]).multiplyScalar(scale);
    const dir = new THREE.Vector3().subVectors(p1, p0);
    const len = dir.length();
    const mid = new THREE.Vector3().addVectors(p0, p1).multiplyScalar(0.5);
    const bs = new THREE.Mesh(new THREE.CylinderGeometry(0.04 * scale, 0.04 * scale, len, 6), bmat);
    bs.position.copy(mid);
    bs.quaternion.setFromUnitVectors(new THREE.Vector3(0, 1, 0), dir.normalize());
    g.add(bs);
  });
  g.position.set(x, y, z);
  molGroup.add(g);
  molData.push({ g, baseY: y, spd: 0.3 + Math.random() * 0.3, phase: Math.random() * Math.PI * 2, rotSpd: 0.15 + Math.random() * 0.2 });
}
// Place molecules along corridor
const molPositions = [
  [4, 3.5, 0], [-3, 4, 0], [5, 2.8, -15], [-4, 3.2, -15],
  [3, 4.2, -30], [-5, 3, -28], [4, 3, -45], [-3, 4, -50],
  [5, 3.5, -65], [3, 2.8, -75], [-4, 4, -88], [5, 3, -100],
  [-3, 3.5, -115], [4, 4, -130], [-5, 3, -145], [3, 3.5, -158]
];
molPositions.forEach(([x, y, z]) => makeMol(x, y, z, 0.9 + Math.random() * 0.4));

// ═══════════════════════════════════════════════════════
//  SCROLL SYSTEM
// ═══════════════════════════════════════════════════════
const TOTAL_SCENES = 6;
const SCENE_LABELS = ['Entrance', 'Laboratory', 'Products', 'Warehouse', 'Dispatch', 'Contact'];

// Camera target positions per scene
const CAM = [
  { px: 0, py: 2.0, pz: 9, lx: 0, ly: 2.0, lz: 0 },
  { px: -1, py: 2.2, pz: -10, lx: -2, ly: 1.8, lz: -25 },
  { px: 0, py: 2.0, pz: -35, lx: 0, ly: 2.0, lz: -52 },
  { px: 1, py: 2.5, pz: -70, lx: 0, ly: 2.2, lz: -90 },
  { px: 0, py: 2.2, pz: -112, lx: 0, ly: 1.8, lz: -130 },
  { px: 0, py: 2.2, pz: -148, lx: 0, ly: 3.0, lz: -162 },
];

let tgt = { ...CAM[0] };
let cur2 = { px: 0, py: 2, pz: 9, lx: 0, ly: 2, lz: 0 };
let activeSc = 0;

const PANELS = { 1: 'ip1', 3: 'ip3' };
const OVERLAYS = { 2: 'pgrid', 4: 'icloud', 5: 'cbox' };

function showScene(sc) {
  // Labels
  for (let i = 0; i < TOTAL_SCENES; i++) {
    const el = document.getElementById('s' + i);
    if (el) el.classList.toggle('on', i === sc);
  }
  // Side panels
  ['ip1', 'ip3', 'product-sidebar'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.classList.remove('on');
  });

  if (sc === 2 && currentCategory) {
    setTimeout(() => document.getElementById('product-sidebar').classList.add('on'), 200);
  }

  if (sc !== 2) {
    currentCategory = null;
  }

  if (PANELS[sc]) setTimeout(() => document.getElementById(PANELS[sc]).classList.add('on'), 200);

  // Overlays
  ['pgrid', 'icloud', 'cbox'].forEach(id => {
    const el = document.getElementById(id);
    if (el) { el.classList.remove('on'); el.classList.remove('shifted'); }
  });

  if (OVERLAYS[sc]) {
    setTimeout(() => {
      const el = document.getElementById(OVERLAYS[sc]);
      if (el) el.classList.add('on');
    }, 280);
  }

  // Stats
  document.getElementById('stbar').classList.toggle('on', sc === 0);
  // Footer
  document.getElementById('ftr').classList.toggle('on', sc >= 5);
  // Breadcrumb
  document.getElementById('bc').textContent = `Auxinor Chem · ${SCENE_LABELS[sc]}`;
  // Dots
  document.querySelectorAll('.pd').forEach((d, i) => d.classList.toggle('on', i === sc));
}

function onScroll2() {
  const maxScroll = document.documentElement.scrollHeight - innerHeight;
  const pct = Math.max(0, Math.min(1, scrollY / maxScroll));
  const rawSc = pct * (TOTAL_SCENES - 1);
  const s0 = Math.min(Math.floor(rawSc), TOTAL_SCENES - 1);
  const s1 = Math.min(s0 + 1, TOTAL_SCENES - 1);
  const t2 = rawSc - s0;
  const ease = t2 < 0.5 ? 2 * t2 * t2 : 1 - Math.pow(-2 * t2 + 2, 2) / 2;

  const c0 = CAM[s0], c1 = CAM[s1];
  tgt = {
    px: c0.px + (c1.px - c0.px) * ease,
    py: c0.py + (c1.py - c0.py) * ease,
    pz: c0.pz + (c1.pz - c0.pz) * ease,
    lx: c0.lx + (c1.lx - c0.lx) * ease,
    ly: c0.ly + (c1.ly - c0.ly) * ease,
    lz: c0.lz + (c1.lz - c0.lz) * ease,
  };

  document.getElementById('sprog').style.height = (pct * 100) + '%';

  const newSc = Math.round(rawSc);
  if (newSc !== activeSc) { activeSc = newSc; showScene(activeSc); }
}
window.addEventListener('scroll', onScroll2, { passive: true });

function goTo(idx) {
  const maxScroll = document.documentElement.scrollHeight - innerHeight;
  window.scrollTo({ top: (idx / (TOTAL_SCENES - 1)) * maxScroll, behavior: 'smooth' });
}
document.querySelectorAll('.pd').forEach(d => d.addEventListener('click', () => goTo(+d.dataset.i)));

// ═══════════════════════════════════════════════════════
//  RENDER LOOP
// ═══════════════════════════════════════════════════════
const clk = new THREE.Clock();
const lookVec = new THREE.Vector3();

function render() {
  requestAnimationFrame(render);
  const dt = clk.getDelta();
  const et = clk.getElapsedTime();
  const ls = 3.8;

  cur2.px += (tgt.px - cur2.px) * ls * dt;
  cur2.py += (tgt.py - cur2.py) * ls * dt;
  cur2.pz += (tgt.pz - cur2.pz) * ls * dt;
  cur2.lx += (tgt.lx - cur2.lx) * ls * dt;
  cur2.ly += (tgt.ly - cur2.ly) * ls * dt;
  cur2.lz += (tgt.lz - cur2.lz) * ls * dt;

  camera.position.set(
    cur2.px + Math.sin(et * 0.38) * 0.06,
    cur2.py + Math.sin(et * 0.55) * 0.04,
    cur2.pz
  );
  lookVec.set(cur2.lx, cur2.ly, cur2.lz);
  camera.lookAt(lookVec);

  // Animate molecules
  molData.forEach(m => {
    m.g.position.y = m.baseY + Math.sin(et * m.spd + m.phase) * 0.12;
    m.g.rotation.y += dt * m.rotSpd;
  });

  renderer.render(scene, camera);
}
render();

// ═══════════════════════════════════════════════════════
//  CURSOR
// ═══════════════════════════════════════════════════════
const curEl = document.getElementById('cur'), ringEl = document.getElementById('cur-ring');
let cmx = 0, cmy = 0, crx = 0, cry = 0;
document.addEventListener('mousemove', e => {
  cmx = e.clientX; cmy = e.clientY;
  curEl.style.left = cmx + 'px'; curEl.style.top = cmy + 'px';
});
(function trackCur() {
  crx += (cmx - crx) * 0.1; cry += (cmy - cry) * 0.1;
  ringEl.style.left = crx + 'px'; ringEl.style.top = cry + 'px';
  requestAnimationFrame(trackCur);
})();
document.querySelectorAll('button,a,.pd,.ipill,.pcell').forEach(el => {
  el.addEventListener('mouseenter', () => { ringEl.style.width = '44px'; ringEl.style.height = '44px' });
  el.addEventListener('mouseleave', () => { ringEl.style.width = '30px'; ringEl.style.height = '30px' });
});

// ═══════════════════════════════════════════════════════
//  LOADER
// ═══════════════════════════════════════════════════════
setTimeout(() => {
  const ld = document.getElementById('loader');
  ld.style.opacity = '0';
  setTimeout(() => { ld.style.display = 'none'; showScene(0); }, 800);
}, 2700);

window.addEventListener('scroll', () => {
  document.getElementById('nav').classList.toggle('scrolled', scrollY > 60);
}, { passive: true });

function sendMsg() {
  const t = document.getElementById('toast');
  t.classList.add('on');
  setTimeout(() => t.classList.remove('on'), 3500);
}

// Interactive Products Logic
function viewCategory(cat) {
  currentCategory = cat;

  const psTitle = document.getElementById('ps-title');
  const psList = document.getElementById('ps-list');

  psTitle.textContent = cat;
  psList.innerHTML = '';

  if (productData[cat]) {
    productData[cat].forEach(p => {
      const li = document.createElement('li');
      li.className = 'ps-item';
      li.textContent = p;
      li.onclick = () => showProductModal(p, cat);
      psList.appendChild(li);
    });
  }

  // Change ring cursor on dynamic items
  document.querySelectorAll('.ps-item').forEach(el => {
    el.addEventListener('mouseenter', () => { ringEl.style.width = '44px'; ringEl.style.height = '44px' });
    el.addEventListener('mouseleave', () => { ringEl.style.width = '30px'; ringEl.style.height = '30px' });
  });

  goTo(2);
  if (activeSc !== 2) activeSc = 2;
  showScene(2);
}

function backToProducts() {
  currentCategory = null;
  showScene(2);
}

function showProductModal(prod, cat) {
  document.getElementById('pm-title').textContent = prod;
  document.getElementById('pm-tag').textContent = cat;

  const info = productInfo[prod] || {
    desc: "Premium industrial-grade " + prod + " optimized for high performance. Sourced from leading manufacturers and subjected to rigorous quality checks before distribution across India.",
    specs: { "Grade": "Industrial / High-Purity", "Packaging": "Drums, ISO Tanks, Bulk" }
  };

  document.getElementById('pm-desc').textContent = info.desc;

  const specsContainer = document.querySelector('.pm-specs');
  specsContainer.innerHTML = '';

  Object.keys(info.specs).forEach(key => {
    const row = document.createElement('div');
    row.className = 'pm-spec';
    row.innerHTML = `<span class="cl">${key}</span><span class="cv">${info.specs[key]}</span>`;
    specsContainer.appendChild(row);
  });

  document.getElementById('product-modal').classList.add('on');
}

function closeModal() {
  document.getElementById('product-modal').classList.remove('on');
}