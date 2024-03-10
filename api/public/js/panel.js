// Global variables
window.baseURL = document.body.getAttribute('data-url')

/**
 * Edit
 * @param {string} endpoint 
 * @param {integer} id 
 * @returns 
 */
window.edit = async (endpoint, id) => {
  window.loading(true)
  const requestOptions = {
    method: 'GET',
    redirect: 'follow',
  }
  const data =  await fetch(`${baseURL}panel/${endpoint}/info/${id}`, requestOptions).then((response) => response.json())
  window.loading(false)
  return data
}

/**
 * Loading
 * @param {void} state
 */
window.loading = (state = false) => {
  const l = document.querySelector('#loading')
  l ? (l.style.opacity = state) : null
  l ? (l.style.visibility = state ? 'visible' : 'hidden') : null
} // Bind to the window

let documentLocation = {
  status: false,
  init: () => {
    let windowPath = window.location.pathname.split('/')
    let l = windowPath[windowPath.length - 1]
    if (!!document.querySelector('#' + l)) document.querySelector('#' + l).classList.add('active')
  },
  go: (path) => {
    window.location.href = path
  },
}

window.addEventListener('load', () => {
  loading(false)
  documentLocation.init()

  // if (window.fetch) {
  //     // run my fetch request here
  // } else {
  //     // do something with XMLHttpRequest?
  // }
})
