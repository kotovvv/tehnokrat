const path = require("path");
//const nodeExternals = require('webpack-node-externals');
module.exports = {
  // externals: {
  //   react: 'react',
  //   reactDom: 'react-dom'
  // },
  entry: {
    "widget-product-variation-selector": [path.resolve(__dirname, "src", "js", "index.js")],
    "widget-one-product": [path.resolve(__dirname, "src", "js", "indexOneProduct.js")],
  },
  mode: "production",// development, production
  target: 'web', // in order too ignore built-in modules like path, fs, node etc.

  //externals: [nodeExternals()], // in order to ignore all modules in node_modules folder
  output: {
    filename: "js/[name].js",
    path: path.resolve(__dirname, "assets"),
  },
  module: {
    // exclude node_modules
    rules: [
      {
        test: /\.(js)$/,
        exclude: /node_modules/,
        use: ["babel-loader"],
      },
    ],
  },
  // pass all js files through Babel
  resolve: {
    extensions: ["*", ".js"],
  },

};