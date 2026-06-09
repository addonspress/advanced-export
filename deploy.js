const fs = require('fs');
const path = require('path');
const { execSync } = require('child_process');

const ROOT = __dirname;
const PKG = JSON.parse(fs.readFileSync(path.join(ROOT, 'package.json'), 'utf8'));
const NAME = PKG.name;
const BUILD_DIR = path.join(ROOT, 'deploy', NAME);
const ZIP_NAME = `${NAME}.zip`;

const PRODUCTION_FILES = [
	'advanced-export.php',
	'uninstall.php',
	'index.php',
	'LICENSE.txt',
	'readme.txt',
	'admin',
	'includes',
	'languages',
	'assets',
];

function copy(src, dest) {
	const stat = fs.statSync(src);
	if (stat.isDirectory()) {
		fs.mkdirSync(dest, { recursive: true });
		for (const entry of fs.readdirSync(src)) {
			copy(path.join(src, entry), path.join(dest, entry));
		}
	} else {
		fs.mkdirSync(path.dirname(dest), { recursive: true });
		fs.copyFileSync(src, dest);
	}
}

console.log(`\nPreparing production release: ${NAME} v${PKG.version}\n`);

// Clean deploy directory
if (fs.existsSync(path.join(ROOT, 'deploy'))) {
	fs.rmSync(path.join(ROOT, 'deploy'), { recursive: true });
}

fs.mkdirSync(BUILD_DIR, { recursive: true });

// Copy production files
for (const file of PRODUCTION_FILES) {
	const srcPath = path.join(ROOT, file);
	const destPath = path.join(BUILD_DIR, file);
	if (fs.existsSync(srcPath)) {
		copy(srcPath, destPath);
		console.log(`  ✓ ${file}`);
	} else {
		console.warn(`  ⚠ ${file} not found — skipping`);
	}
}

console.log(`\nFiles assembled in deploy/${NAME}/`);

// Create ZIP
try {
	execSync(`cd deploy && zip -r "${ZIP_NAME}" "${NAME}"`, { stdio: 'inherit' });
	console.log(`\n✓ ZIP created: deploy/${ZIP_NAME}`);
} catch (err) {
	console.warn('\n⚠ ZIP creation failed (zip command not available?). Files are in deploy/ directory.');
}

console.log('\nDeploy complete.\n');
