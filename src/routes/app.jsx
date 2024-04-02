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
import PinkCheckmark from './../../src/assets/verified.svg'
import GitHubMark from './../../src/assets/github-mark.svg'
import IconX from './../../src/assets/icon-x.svg'
import IconCG from './../../src/assets/icon-cg.svg'
import IconTelegram from './../../src/assets/icon-telegram.svg'
import IconDiscord from './../../src/assets/icon-discord.svg'
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
  const [manager, setManager] = useState()
  const [like, setLike] = useState(0)
  const auth = useAuth()
  const navigate = useNavigate()
  const params = useParams()

  const fetchIPFS = async (CID) => {
    try {
      const response = await fetch(`${import.meta.env.VITE_IPFS_GATEWAY}${CID}`)
      if (!response.ok) throw new Response('Failed to get data', { status: 500 })
      const json = await response.json()
      // console.log(json)
      return json
    } catch (error) {
      console.error(error)
    }
  }

  const getApp = async () => {
    let web3 = new Web3(import.meta.env.VITE_RPC_URL)
    const UpstoreContract = new web3.eth.Contract(ABI, import.meta.env.VITE_UPSTORE_CONTRACT_MAINNET)
    return await UpstoreContract.methods.getApp(params.appId).call()
  }

  const getLike = async () => {
    let web3 = new Web3(import.meta.env.VITE_RPC_URL)
    const UpstoreContract = new web3.eth.Contract(ABI, import.meta.env.VITE_UPSTORE_CONTRACT_MAINNET)
    return await UpstoreContract.methods.getLikeTotal(params.appId).call()
  }

  const handleLike = async () => {
    if (!auth.wallet) {
      toast.error(`Please connect Universal Profile`)
      return
    }

    const t = toast.loading(`Waiting for transaction's confirmation`)

    try {
      let web3 = new Web3(window.lukso)
      web3.eth.defaultAccount = auth.wallet
      const UpstoreContract = new web3.eth.Contract(ABI, import.meta.env.VITE_UPSTORE_CONTRACT_MAINNET)
      return await UpstoreContract.methods
        .setLike(params.appId)
        .send({ from: auth.wallet })
        .then((res) => {
          console.log(res)
          toast.dismiss(t)

          // Refetch the like total
          getLike().then((res) => {
            setLike(web3.utils.toNumber(res))
          })

          // Party
          party.confetti(document.querySelector(`.party-holder`), {
            count: party.variation.range(20, 40),
            shapes: ['star', 'roundedSquare'],
          })
        })
    } catch (error) {
      console.error(error)
      toast.dismiss(t)
    }
  }

  useEffect(() => {
    getApp().then(async (res) => {
      if (!res.status) return
      const responses = Object.assign(await fetchIPFS(res.metadata), res)
      setApp([responses])
      setIsLoading(false)
      auth.fetchProfile(responses.manager).then((res) => {
        setManager(res.LSP3Profile)
        console.log(res.LSP3Profile)
      })
    })

    getLike().then((res) => {
      setLike(web3.utils.toNumber(res))
    })
  }, [])

  return (
    <>
      <section className={`${styles.section} s-motion-slideUpIn`}>
        <div className={`__container`} data-width={`large`}>
          {isLoading && (
            <>
              {[1].map((item, i) => (
                <Shimmer key={i}>
                  <div style={{ width: `50px`, height: `50px` }} />
                </Shimmer>
              ))}
            </>
          )}

          <div className={`ms-Grid`} dir="ltr">
            <div className={`ms-Grid-row`}>
              <div className={`ms-Grid-col ms-sm12 ms-md4 ms-lg4`}>
                {app &&
                  app.length > 0 &&
                  app.map((item, i) => (
                    <div className={`${styles['card']} party-holder`} key={i}>
                      <div
                        style={{ backgroundColor: item.style && JSON.parse(item.style).backgroundColor }}
                        className={`${styles['card__body']} d-flex flex-column align-items-center justify-content-center animate fade`}
                        key={i}
                      >
                        <figure className={`${styles['logo']}`} title={item.category}>
                          <img alt={item.name} src={item.logo} />
                          <figcaption>
                            {item.name}
                            <img src={PinkCheckmark} />
                          </figcaption>
                        </figure>
                        <div className={`mt-10`}>
                          {app[0].tags &&
                            app[0].tags.split(',').map((tag, i) => (
                              <span key={i} className={`badge badge-danger badge-pill ml-10`}>
                                {tag}
                              </span>
                            ))}
                        </div>
                      </div>
                    </div>
                  ))}

                <div className={`${styles['card']} mt-10`}>
                  <div className={`${styles['card__body']} animate fade`}>
                    {app && app.length > 0 && app[0].tags && (
                      <div className={`d-flex`}>
                        <span>🔒</span>
                        <span>{app[0].url}</span>
                      </div>
                    )}
                  </div>
                </div>

                {app && app.length > 0 && app[0].repo && (
                  <>
                    <div className={`${styles['card']} ${styles['repo']} mt-10`}>
                      <div className={`${styles['card__body']} animate fade`}>
                        <div className={`d-flex flex-row align-items-center justify-content-start`}>
                          <img src={GitHubMark} />
                          <a href={`${app[0].repo}`} target={`_blank`}>
                            <span className={`badge badge-dark badge-pill ml-10`}>{app[0].repo}</span>
                          </a>
                        </div>
                      </div>
                    </div>
                  </>
                )}

                {app && app.length > 0 && manager && (
                  <>
                    <div className={`${styles['card']} ${styles['repo']} mt-10`}>
                      <div className={`${styles['card__header']} d-flex flex-row align-items-center justify-content-between`}>
                        <span>Owner</span>
                        <a target={`_blank`} href={`https://wallet.universalprofile.cloud/${app[0].manager}?referrer=UPStore&network=mainnet`}>
                          View
                        </a>
                      </div>
                      <div className={`${styles['card__body']} animate fade d-flex flex-column align-items-center`}>
                        <figure className={`${styles['owner']}`}>
                          <img alt={``} src={`https://ipfs.io/ipfs/${manager?.profileImage[0]?.url.replace('ipfs://', '').replace('://', '')}`} />
                          <figcaption>@{manager?.name}</figcaption>
                        </figure>
                        <p title={app[0].manager}>{`${app[0].manager.slice(0, 6)}...${app[0].manager.slice(38)}`}</p>
                      </div>
                    </div>
                  </>
                )}

                <div className={`${styles['card']} ${styles['repo']} mt-10`}>
                  <div className={`${styles['card__body']} animate fade d-flex flex-column align-items-center`}>
                    <figure className={`${styles['qr']}`}>
                      <img src={`https://quickchart.io/qr?text=${window.location.href}&size=200&format=svg`} />
                    </figure>
                  </div>
                </div>
              </div>

              <div className={`ms-Grid-col ms-sm12 ms-md8 ms-lg8`}>
                {app && app.length > 0 && (
                  <>
                    <div className={`${styles['card']}`}>
                      <div className={`${styles['card__header']} d-flex flex-row align-items-center justify-content-between`}>
                        <span>Description</span>
                        <span className={`badge badge-pill badge-warning`}>#{app[0].category}</span>
                      </div>

                      <div style={{ backgroundColor: app[0].style && JSON.parse(app[0].style).backgroundColor }} className={`${styles['card__body']} ${styles['description']}`}>
                        {app[0].description}
                      </div>
                    </div>

                    <div className={`${styles['button']} mt-20 mb-20 d-flex flex-row align-items-center justify-content-between`}>
                      <a href={`${app[0].url}`} target={`_blank`}>
                        Open
                        <svg width="22" height="21" viewBox="0 0 22 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path
                            d="M16.25 9.1875V16.7344C16.25 16.9498 16.2076 17.1632 16.1251 17.3622C16.0427 17.5613 15.9218 17.7421 15.7695 17.8945C15.6171 18.0468 15.4363 18.1677 15.2372 18.2501C15.0382 18.3326 14.8248 18.375 14.6094 18.375H4.76562C4.3305 18.375 3.9132 18.2021 3.60553 17.8945C3.29785 17.5868 3.125 17.1695 3.125 16.7344V6.89062C3.125 6.4555 3.29785 6.0382 3.60553 5.73053C3.9132 5.42285 4.3305 5.25 4.76562 5.25H11.6349M14.2812 2.625H18.875V7.21875M9.6875 11.8125L18.5469 2.95312"
                            stroke="white"
                            strokeWidth="1.3125"
                            strokeLinecap="round"
                            strokeLinejoin="round"
                          />
                        </svg>
                      </a>

                      {app[0].social && (
                        <ul className={`${styles['social']} d-flex flex-row align-items-center justify-content-between`}>
                          {app[0].social.x && (
                            <li>
                              <a href={`https://twitter.com/${app[0].social.x}`} target={`_blank`}>
                                <figure>
                                  <img alt={`X`} src={IconX} />
                                </figure>
                              </a>
                            </li>
                          )}

                          {app[0].social.cg && (
                            <li>
                              <a href={`https://app.cg/c/${app[0].social.cg}`} target={`_blank`}>
                                <figure>
                                  <img alt={`CG`} src={IconCG} />
                                </figure>
                              </a>
                            </li>
                          )}

                          {app[0].social.telegram && (
                            <li>
                              <a href={`https://t.me/${app[0].social.telegram}`} target={`_blank`}>
                                <figure>
                                  <img alt={`Telegram`} src={IconTelegram} />
                                </figure>
                              </a>
                            </li>
                          )}

                          {app[0].social.discord && (
                            <li>
                              <a href={`https://discord.gg/${app[0].social.discord}`} target={`_blank`}>
                                <figure>
                                  <img alt={`Discord`} src={IconDiscord} />
                                </figure>
                              </a>
                            </li>
                          )}
                        </ul>
                      )}
                    </div>

                    <div className={`${styles['card']}`}>
                      <div className={`${styles['card__body']}`}>
                        <p>App ID: {params.appId && `${params.appId.slice(0, 8)}...${params.appId.slice(60)}`}</p>
                      </div>
                    </div>

                    <p className={`${styles['like']} d-flex align-items-center`}>
                      <span>Like this dapp?</span>

                      <button className={`${styles['btn-like']}`} onClick={() => handleLike()}>
                        <MaterialIcon name={`favorite`} />
                      </button>

                      <span>{like}</span>
                    </p>
                  </>
                )}
              </div>
            </div>
          </div>
        </div>
      </section>
    </>
  )
}

export default App
