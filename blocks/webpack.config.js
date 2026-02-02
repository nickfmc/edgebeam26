const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const path = require('path');
const fs = require('fs');

// Helper function to copy file with retry logic for Windows
function copyFileWithRetry(src, dest, retries = 3, delay = 100) {
    for (let i = 0; i < retries; i++) {
        try {
            fs.copyFileSync(src, dest);
            return true;
        } catch (error) {
            if (i === retries - 1) {
                // Last attempt failed, throw the error
                console.error(`Failed to copy ${src} to ${dest} after ${retries} attempts:`, error.message);
                throw error;
            }
            // Wait before retrying
            const waitMs = delay * (i + 1);
            const start = Date.now();
            while (Date.now() - start < waitMs) {
                // Blocking wait
            }
        }
    }
    return false;
}

// Function to get all block directories
function getBlockEntries() {
    const blocksDir = path.resolve(__dirname, 'src');
    const entries = {};
    
    if (fs.existsSync(blocksDir)) {
        const blockFolders = fs.readdirSync(blocksDir, { withFileTypes: true })
            .filter(dirent => dirent.isDirectory())
            .map(dirent => dirent.name);
        
        blockFolders.forEach(blockName => {
            const blockPath = path.resolve(blocksDir, blockName);
            const indexPath = path.resolve(blockPath, 'index.js');
            const frontendPath = path.resolve(blockPath, 'frontend.js');
            const stylePath = path.resolve(blockPath, 'style.scss');
            
            // Add editor script entry
            if (fs.existsSync(indexPath)) {
                entries[`${blockName}/index`] = indexPath;
            }
            
            // Add frontend script entry
            if (fs.existsSync(frontendPath)) {
                entries[`${blockName}/frontend`] = frontendPath;
            }
            
            // Add style entry for SCSS files
            if (fs.existsSync(stylePath)) {
                entries[`${blockName}/style`] = stylePath;
            }
        });
    }
    
    return entries;
}

// Custom webpack config
const config = {
    ...defaultConfig,
    entry: getBlockEntries(),
    output: {
        ...defaultConfig.output,
        path: path.resolve(__dirname, 'build'),
        filename: '[name].js',
    },
    plugins: [
        ...defaultConfig.plugins,
        // Copy block.json files to build directory
        {
            apply: (compiler) => {
                compiler.hooks.afterEmit.tap('CopyBlockJson', () => {
                    const srcDir = path.resolve(__dirname, 'src');
                    const buildDir = path.resolve(__dirname, 'build');
                    
                    if (fs.existsSync(srcDir)) {
                        const blockFolders = fs.readdirSync(srcDir, { withFileTypes: true })
                            .filter(dirent => dirent.isDirectory())
                            .map(dirent => dirent.name);
                        
                        blockFolders.forEach(blockName => {
                            const srcBlockJson = path.resolve(srcDir, blockName, 'block.json');
                            const buildBlockDir = path.resolve(buildDir, blockName);
                            const buildBlockJson = path.resolve(buildBlockDir, 'block.json');
                            
                            if (fs.existsSync(srcBlockJson)) {
                                // Create build directory if it doesn't exist
                                if (!fs.existsSync(buildBlockDir)) {
                                    fs.mkdirSync(buildBlockDir, { recursive: true });
                                }
                                
                                // Copy block.json with retry
                                try {
                                    copyFileWithRetry(srcBlockJson, buildBlockJson);
                                } catch (error) {
                                    console.warn(`Warning: Could not copy block.json for ${blockName}`);
                                }
                                
                                // Also copy render.php if it exists
                                const srcRenderPhp = path.resolve(srcDir, blockName, 'render.php');
                                const buildRenderPhp = path.resolve(buildBlockDir, 'render.php');
                                
                                if (fs.existsSync(srcRenderPhp)) {
                                    try {
                                        copyFileWithRetry(srcRenderPhp, buildRenderPhp);
                                    } catch (error) {
                                        console.warn(`Warning: Could not copy render.php for ${blockName}`);
                                    }
                                }
                            }
                        });
                    }
                });
            }
        }
    ],
};

module.exports = config;
