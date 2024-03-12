import { useEffect, useRef, useState } from 'react'
import { useNavigate, defer, useParams } from 'react-router-dom'
import { Title } from './helper/DocumentTitle'
import MaterialIcon from './helper/MaterialIcon'
import Shimmer from './helper/Shimmer'
import Loading from './components/LoadingSpinner'
import { CheckIcon, ChromeIcon, BraveIcon } from './components/icons'
import toast, { Toaster } from 'react-hot-toast'
import { useAuth, web3, _ } from '../contexts/AuthContext'
import styles from './App.module.scss'
import Logo from './../../src/assets/logo.svg'
import Banner from './../../src/assets/banner.png'
import Web3 from 'web3'
import ABI from '../abi/upstore.json'
import party from 'party-js'
// import { getApp } from './../util/api'
import DappDefaultIcon from './../assets/dapp-default-icon.svg'

export const loader = async ({ request, params }) => {
  if (params.appId.length !== `0x0000000000000000000000000000000000000000000000000000000000000000`.length) return false
  if (localStorage.getItem(`appSeen`) !== null) {
    let data = JSON.parse(localStorage.getItem(`appSeen`))
    if (data.filter((item) => item.appId.includes(params.appId)).length === 0) {
      let newData = [...JSON.parse(localStorage.getItem(`appSeen`)), { appId: params.appId }]
      localStorage.setItem(`appSeen`, JSON.stringify(newData))
    }
  } else {
    let newData = [{ appId: params.appId }]
    localStorage.setItem(`appSeen`, JSON.stringify(newData))
  }

  return defer({})
}

function App({ title }) {
  Title(title)
  const [isLoading, setIsLoading] = useState(true)
  const [app, setApp] = useState([])
  const auth = useAuth()
  const navigate = useNavigate()
  const params = useParams()
  const txtSearchRef = useRef()

  const fetchIPFS = async (CID) => {
    try {
      const response = await fetch(`https://api.universalprofile.cloud/ipfs/${CID}`)
      if (!response.ok) throw new Response('Failed to get data', { status: 500 })
      const json = await response.json()
      // console.log(json)
      return json
    } catch (error) {
      console.error(error)
    }
  }

  const getApp = async () => {
    let web3 = new Web3(`https://rpc.testnet.lukso.gateway.fm`)
    web3.eth.defaultAccount = params.appId
    const UpstoreContract = new web3.eth.Contract(ABI, import.meta.env.VITE_UPSTORE_CONTRACT_MAINNET)
    return await UpstoreContract.methods.getApp(params.appId).call()
  }

  useEffect(() => {
    getApp().then(async (res) => {
      const responses = await fetchIPFS(res.metadata)
      console.log(responses)
      setApp([responses])
      setIsLoading(false)
    })
  }, [])

  return (
    <>
      <section className={styles.section}>
        <div className={`__container`} data-width={`medium`}>
          <div className={`${styles['card']} card`}>
            {isLoading && (
              <>
                {[1].map((item, i) => (
                  <Shimmer key={i}>
                    <div style={{ width: `50px`, height: `50px` }} />
                  </Shimmer>
                ))}
              </>
            )}

            {app &&
              app.length > 0 &&
              app.map((item, i) => (
                <div
                  style={{ backgroundColor: item.style && JSON.parse(item.style).backgroundColor }}
                  className={`${styles['card__body']} card__body d-flex flex-column align-items-center justify-content-center animate fade`}
                  key={i}
                >
                  <figure title={item.category}>
                    <img alt={item.name} src={item.logo} />
                  </figure>

                  <h4>{item.name}</h4>
                  <p>{item.description}</p>
                  <b>
                    App ID: {params.appId.slice(0, 4)}...{params.appId.slice(params.appId.length - 4, params.appId.length)}
                  </b>
                  <div>
                    {item.tags &&
                      item.tags.split(',').map((tag, i) => (
                        <span key={i} className={`badge badge-dark badge-pill`}>
                          {tag}
                        </span>
                      ))}
                  </div>

                  <p className=" mt-10 d-flex">
                    <span className={`badge badge-pill badge-success`}>{item.url}</span>
                    <a href={`${item.url}`} target={`_blank`}>
                      <svg width="22" height="21" viewBox="0 0 22 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                          d="M16.25 9.1875V16.7344C16.25 16.9498 16.2076 17.1632 16.1251 17.3622C16.0427 17.5613 15.9218 17.7421 15.7695 17.8945C15.6171 18.0468 15.4363 18.1677 15.2372 18.2501C15.0382 18.3326 14.8248 18.375 14.6094 18.375H4.76562C4.3305 18.375 3.9132 18.2021 3.60553 17.8945C3.29785 17.5868 3.125 17.1695 3.125 16.7344V6.89062C3.125 6.4555 3.29785 6.0382 3.60553 5.73053C3.9132 5.42285 4.3305 5.25 4.76562 5.25H11.6349M14.2812 2.625H18.875V7.21875M9.6875 11.8125L18.5469 2.95312"
                          stroke="black"
                          strokeWidth="1.3125"
                          strokeLinecap="round"
                          strokeLinejoin="round"
                        />
                      </svg>
                    </a>
                  </p>
                </div>
              ))}
          </div>
        </div>
      </section>
    </>
  )
}

export default App