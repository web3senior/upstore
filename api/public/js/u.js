import { addEvent } from './api'
import Toastify from 'toastify-js'
import Web3 from 'web3'

//const RPC_URL = `https://rpc.testnet.lukso.gateway.fm`
export const PROVIDER = window.lukso
export const web3 = new Web3(PROVIDER)
const schemas = [
  {
    name: 'SupportedStandards:LSP3Profile',
    key: '0xeafec4d89fa9619884b600005ef83ad9559033e6e941db7d7c495acdce616347',
    keyType: 'Mapping',
    valueType: 'bytes',
    valueContent: '0x5ef83ad9',
  },
  {
    name: 'LSP3Profile',
    key: '0x5ef83ad9559033e6e941db7d7c495acdce616347d28e90c7ce47cbfcfcad3bc5',
    keyType: 'Singleton',
    valueType: 'bytes',
    valueContent: 'VerifiableURI',
  },
  {
    name: 'LSP1UniversalReceiverDelegate',
    key: '0x0cfc51aec37c55a4d0b1a65c6255c4bf2fbdf6277f3cc0730c45b828b6db8b47',
    keyType: 'Singleton',
    valueType: 'address',
    valueContent: 'Address',
  },
]

// Data members
const baseUrl = document.body.dataset.baseUrl
const APIURL = document.body.dataset.apiUrl
const googleRecaptchaSiteKey = document.body.dataset.googleRecaptchaSiteKey
const googleClientId = document.body.dataset.googleClientId
const loadingElement = document.querySelector('.loading')
const eForm = document.forms[0]
const eSubmitter = document.querySelector('button[type=submit]')
const output = document.querySelector('output')
const btnCopyElement = document.querySelector('.btn-copy')
const btnVoic3Start = document.querySelector('.btn-voic3-start')
const btnVoic3Stop = document.querySelector('.btn-voic3-stop')
const btnVoic3Send = document.querySelector('.btn-voic3-send')

// Global variables
let accounts

/**
 * Form
 */
const form = () => {
  document.forms[0].addEventListener('submit', async (event) => {
    event.preventDefault()
    // grecaptcha.ready(async() => {
    // grecaptcha.execute(googleRecaptchaSiteKey, { action: 'submit' }).then(async (token) => {
    // Add your logic to submit to your backend server here.
    const formData = new FormData(eForm, eSubmitter)

    const email = formData.get('email'),
      password = formData.get('password'),
      frmToken = formData.get('toekn'),
      googleRecaptchaToken = token

    if (email.length === 0 || email.length > 30) {
      eForm.email.focus()
      output.textContent = 'ایمیل را صحیح وارد کنید'
      return false
    }

    if (password.length === 0 || password.length > 50) {
      output.textContent = 'پسورد را صحیح وارد کنید'
      return false
    }

    loading(true)

    var myHeaders = new Headers()
    myHeaders.append('Content-Type', 'text/plain')

    var requestOptions = {
      method: 'POST',
      headers: myHeaders,
      body: `{"email":"${email}","password":"${password}","googleRecaptchaToken":"${googleRecaptchaToken}"}`,
      redirect: 'follow',
    }

    await fetch(`${baseUrl}admin/auth`, requestOptions)
      .then((response) => response.json())
      .then((result) => {
        console.log(result)
        if (result.result) {
          sessionStorage.setItem('loginDate', new Date().getUTCDate().toString())
          setCookie('token', result.token)
          setCookie('admin_info', JSON.stringify(result.admin_info))
          console.log(result.admin_info.admin_id)
          location.replace(result.message)
        } else {
          alert(result.message)
          window.location.reload(true)
          loading(false)
        }
      })
      .catch((error) => console.log('error', error))
    // })
    //  })
  })
}

/**
 * Loading
 * @param {} state
 */
const loading = (state = false) => {
  loadingElement.style.opacity = state ? '1' : '0'
  loadingElement.style.visibility = state ? 'visible' : 'hidden'
  // loadingElement.remove()
}

/**
 *
 * @param {object} response
 */
const handleCredentialResponse = (response) => {
  console.log('Encoded JWT ID token: ' + response.credential)
}

const toggleLoginButton = () => {
  if (document.forms[0].elements.namedItem('email').value.length === 0 || document.forms[0].elements.namedItem('password').value.length === 0) document.forms[0].elements.namedItem('btnSubmit').disabled = true
  else document.forms[0].elements.namedItem('btnSubmit').disabled = false
}

const changelog = async () => {
  const response = await fetch(`${baseUrl}v1/changelog`)

  if (!response.ok) {
    throw new Response('Failed to fetch links', { status: 500 })
  }
  return response.json()
}

const updateClipboard = (newClip) => {
  navigator.clipboard.writeText(newClip).then(
    () => {
      /* clipboard successfully set */
      Toastify({
        text: 'Link copied',
        duration: 3000,
        destination: '',
        newWindow: true,
        close: true,
        gravity: 'top', // `top` or `bottom`
        position: 'right', // `left`, `center` or `right`
        stopOnFocus: true, // Prevents dismissing of toast on hover
        style: {
          background: 'linear-gradient(to right, #00b09b, #96c93d)',
        },
        onClick: function () {}, // Callback after click
      }).showToast()
    },
    () => {
      /* clipboard write failed */
      console.error('error')
    }
  )
}

const audioRecorder = class {
  constructor() {
    this.audioChunks = []
    this.rec
    this.audioBlob
    this.isRecording = document.getElementById('isRecording')
    this.reset()
  }

  async getUserMedia(constraints) {
    if (window.navigator.mediaDevices) {
      return window.navigator.mediaDevices.getUserMedia(constraints)
    }
    let legacyApi = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia
    if (legacyApi) {
      return new Promise(function (resolve, reject) {
        legacyApi.bind(window.navigator)(constraints, resolve, reject)
      })
    } else {
      alert('user api not supported')
    }
  }

  start() {
    this.isRecording.textContent = 'Recording...'

    this.getUserMedia({ audio: true }).then((stream) => {
      this.rec = new MediaRecorder(stream)
      this.rec.start()
      this.rec.ondataavailable = (e) => {
        this.audioChunks.push(e.data)
        if (this.rec.state == 'inactive') {
          this.audioBlob = new Blob(this.audioChunks, { type: 'audio/ogg' })
          console.log(this.audioBlob)
          document.getElementById('audioElement').src = URL.createObjectURL(this.audioBlob)
        }
      }
    })

    this.reset()
    // Show stop button
    btnVoic3Stop.classList.toggle('d-none')
  }

  pause() {}

  reset() {
    if (!document.getElementById('audioElement').classList.contains('d-none')) document.getElementById('audioElement').classList.add('d-none')
    if (!btnVoic3Send.classList.contains('d-none')) btnVoic3Send.classList.add('d-none')
    if (!btnVoic3Stop.classList.contains('d-none')) btnVoic3Stop.classList.add('d-none')

    this.audioChunks = []
    this.rec = ''
    this.audioBlob = ''
    this.isRecording.textContent = ''
  }

  stop() {
    this.rec.stop()
    this.isRecording.textContent = 'Click play button to start listening'

    // Show audio element and send button. Hide stop button
    document.getElementById('audioElement').classList.toggle('d-none')
    btnVoic3Send.classList.toggle('d-none')
    btnVoic3Stop.classList.toggle('d-none')
  }

  async send() {
    this.isRecording.textContent = 'Sending...'
    const formData = new FormData()
    formData.append('audio', this.audioBlob, 'recording.ogg')

    var requestOptions = {
      method: 'POST',
      body: formData,
      redirect: 'follow',
    }

    const params = new URLSearchParams({ wallet_addr: document.body.dataset.userWalletAddress }).toString()
    const response = await fetch(`https://universallink.me/v1/voice/send?${params}`, requestOptions) //${APIURL}

    if (!response.ok) throw new Response('Failed to get data', { status: 500 })

    let result = await response.json()
    console.log(result)
    if (result.result) {
      Toastify({
        text: 'your message has been sent',
        duration: 3000,
        destination: '',
        newWindow: true,
        close: true,
        gravity: 'top', // `top` or `bottom`
        position: 'right', // `left`, `center` or `right`
        stopOnFocus: true, // Prevents dismissing of toast on hover
        style: {
          background: 'linear-gradient(180deg, #763EE6 0%, #E73E73 100%)',
        },
        onClick: function () {}, // Callback after click
      }).showToast()
      this.reset()
    }
  }
}

function handleAddEvent(username, click, title) {
  addEvent(username, click, title)
}

/**
 * Is UP extension installed?
 * @returns boolean
 */
export const isUPinstalled = () => {
  if (PROVIDER && PROVIDER.isUniversalProfileExtension) return true
  return false
}

/**
 * Connect wallet
 */
export const connectWallet = async () => {
  console.log('Loading...')
  try {
    let accounts = await web3.eth.getAccounts()
    if (accounts.length === 0) await web3.eth.requestAccounts()
    accounts = await web3.eth.getAccounts()
    console.log(`UP successfuly connected`)
    return accounts[0]
  } catch (error) {
    console.error(error.message)
  }
}

const transferTokenToAnAddress = async (addr) => {
  const myToken = new web3.eth.Contract(LSP7Mintable.abi, tokenTransferAddress)

  return await myToken.methods
    .transfer(
      addr, // sender address
      tokenReceiverAddress, // receiving address
      tokenAmount, // token amount
      false, // force parameter
      '0x' // additional data
    )
    .send({ from: addr })
}

/**
 * Connect wallet
 */
export const isWalletConnected = async () => {
  console.info('Check if wallet is connected...')

  try {
    let accounts = await web3.eth.getAccounts()
    return accounts[0]
  } catch (error) {
    toast.error(error.message)
  }
}

/**
 * Initializing
 */
window.addEventListener('load', async () => {
  loading(false)
  const audioRecorderObj = new audioRecorder()

  btnCopyElement.addEventListener('click', () => updateClipboard(btnCopyElement.dataset.copy))

  // Add handler to voice mail
  // Check if the element exist
  if (!!btnVoic3Start) {
    btnVoic3Start.addEventListener('click', () => audioRecorderObj.start())
    btnVoic3Stop.addEventListener('click', () => audioRecorderObj.stop())
    btnVoic3Send.addEventListener('click', () => audioRecorderObj.send())
  }

  // Add handler to all links
  document.querySelectorAll('#link').forEach((item) => {
    item.addEventListener('click', () => {
      handleAddEvent(item.dataset.username, item.dataset.event, item.dataset.title)
    })
  })

  document.querySelector('.tipLyx').addEventListener('click', () => {
    if (isUPinstalled()) {
      connectWallet().then(async (account) => {
        try {
          await web3.eth
            .sendTransaction({
              from: account,
              to: document.body.dataset.userWalletAddress,
              value: web3.utils.toWei('4.2', 'ether'),
            })
            .then((result) => {
              console.log('transfer result =>', result)
              Toastify({
                text: '4.2 LYX has been sent',
              }).showToast()
            })
        } catch (e) {
          console.log(e)
          Toastify({
            text: e.error.message,
            close: true,
            style: {
              background: 'linear-gradient(180deg, #FFE618 41%, #FF7FBF 100%)',
            },
          }).showToast()
        } finally {
          console.log(`Done`)
        }
      })
    }
  })
})

function setCookie(cname, cvalue, exdays = 30) {
  const d = new Date()
  d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000)
  let expires = 'expires=' + d.toUTCString()
  document.cookie = cname + '=' + cvalue + ';' + expires + ';path=/'
}

function getCookie(cname) {
  let name = cname + '='
  let decodedCookie = decodeURIComponent(document.cookie)
  let ca = decodedCookie.split(';')
  for (let i = 0; i < ca.length; i++) {
    let c = ca[i]
    while (c.charAt(0) == ' ') {
      c = c.substring(1)
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length)
    }
  }
  return ''
}

function checkCookie() {
  let username = getCookie('username')
  if (username != '') {
    alert('Welcome again ' + username)
  } else {
    username = prompt('Please enter your name:', '')
    if (username != '' && username != null) {
      setCookie('username', username, 365)
    }
  }
}
