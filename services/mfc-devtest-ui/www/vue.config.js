module.exports = {
  devServer: {
    disableHostCheck: true
  },
  css: {
    loaderOptions: {
      sass: {
        includePaths: [
          "node_modules"
        ]
      }
    }
  }
}
