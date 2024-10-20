const path = require('path')
const { SRC, DIST, ASSETS } = require('./paths')

module.exports = {
  entry: {
    admin: path.resolve(SRC, 'js', 'admin.js'),
    panel: path.resolve(SRC, 'js', 'panel.js'),
    u: path.resolve(SRC, 'js', 'u.js'),
  },
  output: {
    // Put all the bundled stuff in your dist folder
    path: DIST,

    // Our single entry point from above will be named "scripts.js"
    filename: '[name].bundle.js',

    // The output path as seen from the domain we're visiting in the browser
    publicPath: ASSETS
  }
}