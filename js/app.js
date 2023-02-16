/*!
 * PIS
 */

const app = Vue.createApp({
  data() {
    return {}
  },
  methods: {
    disableButton (e) {
      setTimeout(function () {
        e.target.disabled = true
      }, 0)
    },
    disableLinkButton (e) {
      e.target.classList.add('disabled')
    },
    updateSettings (e) {
      setTimeout(function () {
        e.target.disabled = true
      }, 0)
    }
  }
})

app.mount('#app')
