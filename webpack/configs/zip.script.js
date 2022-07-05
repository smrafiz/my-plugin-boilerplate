/**
 * This file holds the configuration that is being used for production zip.
 * This is being imported and extended in the config.distribution.js files
 *
 * @since 1.0.0
 */

const fse = require('fs-extra');
const archiver = require('archiver');

module.exports = (projectOptions) => {
	const projectPath = projectOptions.projectDir;
	const dist = projectOptions.projectPackageDir;
	const slug = projectOptions.projectSlug;
	const copyTo = dist + '/' + slug;

	const copyScript = () => {
		console.log('\n\nStarting the Build =>\n\n');

		fse.ensureDir(copyTo, function (err) {
			if (err) return console.error(err);
			projectOptions.buildIncludes.map((include) => {
				fse.copy(
					projectPath + '/' + include,
					copyTo + '/' + include,
					function (err) {
						if (err) return console.error(err);
						console.log('=> ' + include + ' added...');
					}
				);
			});
		});

		console.log('=> Build directory created');
	};

	const zipScript = () => {
		async function getVersion() {
			let data;
			try {
				data = await fse.readFile(
					projectPath + '/' + slug + '.php',
					'utf-8'
				);
			} catch (err) {
				console.error(err);
			}

			const lines = data.split(/\r?\n/);
			let version = '';
			for (let i = 0; i < lines.length; i++) {
				if (
					lines[i].includes('* Version:') ||
					lines[i].includes('*Version:')
				) {
					version = lines[i]
						.replace('* Version:', '')
						.replace('*Version:', '')
						.trim();
					break;
				}
			}
			return version;
		}

		const getVersionNum = getVersion();

		getVersionNum.then(function (version) {
			const destinationPath = dist + '/' + slug + '.' + version + '.zip';
			const output = fse.createWriteStream(destinationPath);
			const archive = archiver('zip', { zlib: { level: 9 } });
			output.on('close', function () {
				console.log(
					'\n\n=> Zip: ' + archive.pointer() + ' total bytes'
				);
				console.log('\nProject Package Zip has been created.\n');
				fse.removeSync(dist + '/' + slug);
			});
			output.on('end', function () {
				console.log('Data has been drained');
			});
			archive.on('error', function (err) {
				throw err;
			});

			archive.pipe(output);
			archive.directory(dist + '/' + slug, slug);
			archive.finalize();
		});
	};

	return {
		copyScript,
		zipScript,
	};
};
