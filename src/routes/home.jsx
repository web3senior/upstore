import { Suspense, useState, useEffect, useRef } from 'react'
import { useLoaderData, defer, Form, Await, useRouteError, Link, useNavigate } from 'react-router-dom'
import { Title } from './helper/DocumentTitle'
import MaterialIcon from './helper/MaterialIcon'
import Shimmer from './helper/Shimmer'
import Loading from './components/LoadingSpinner'
import { CheckIcon, ChromeIcon, BraveIcon } from './components/icons'
import toast, { Toaster } from 'react-hot-toast'
import { useAuth, web3, _ } from './../contexts/AuthContext'
import styles from './Home.module.scss'
import Logo from './../../src/assets/logo.svg'
import Banner from './../../src/assets/banner.png'
import Web3 from 'web3'
import ABI from './../abi/upstore.json'
import party from 'party-js'
// import { getApp } from './../util/api'
import DappDefaultIcon from './../assets/dapp-default-icon.svg'

party.resolvableShapes['UP'] = `<img src="http://localhost:5173/src/assets/up-logo.svg"/>`
party.resolvableShapes['Lukso'] = `<img src="http://localhost:5173/src/assets/lukso-logo.svg"/>`

const WhitelistFactoryAddr = web3.utils.padLeft(`0x2`, 64)

export const loader = async () => {
  return defer({ key: 'val' })
}

function Home({ title }) {
  Title(title)
  const [loaderData, setLoaderData] = useState(useLoaderData())
  const [isLoading, setIsLoading] = useState(true)
  const [app, setApp] = useState([])
  const [backApp, setBackupApp] = useState([])
  const [whitelist, setWhitelist] = useState()
  const [recentApp, setRecentApp] = useState([])
  const auth = useAuth()
  const navigate = useNavigate()
  const txtSearchRef = useRef()

  const addMe = async () => {
    const t = toast.loading(`Loading`)
    try {
      web3.eth.defaultAccount = auth.wallet

      const whitelistFactoryContract = new web3.eth.Contract(ABI, import.meta.env.VITE_WHITELISTFACTORY_CONTRACT_MAINNET, {
        from: auth.wallet,
      })
      console.log(whitelistFactoryContract.defaultChain, Date.now())
      await whitelistFactoryContract.methods
        .addUser(WhitelistFactoryAddr)
        .send()
        .then((res) => {
          console.log(res)
          toast.dismiss(t)
          toast.success(`You hav been added to the list.`)
          party.confetti(document.querySelector(`h4`), {
            count: party.variation.range(20, 40),
          })
        })
    } catch (error) {
      console.error(error)
      toast.dismiss(t)
    }
  }

  const addUserByManager = async () => {
    const t = toast.loading(`Loading`)
    try {
      web3.eth.defaultAccount = auth.wallet

      const whitelistFactoryContract = new web3.eth.Contract(ABI, import.meta.env.VITE_WHITELISTFACTORY_CONTRACT_MAINNET, {
        from: auth.wallet,
      })

      await whitelistFactoryContract.methods
        .addUserByManager(WhitelistFactoryAddr)
        .send()
        .then((res) => {
          console.log(res)
          toast.dismiss(t)
          toast.success(`You hav been added to the list.`)
          party.confetti(document.querySelector(`h4`), {
            count: party.variation.range(20, 40),
          })
        })
    } catch (error) {
      console.error(error)
      toast.dismiss(t)
    }
  }

  const updateWhitelist = async () => {
    web3.eth.defaultAccount = `0x188eeC07287D876a23565c3c568cbE0bb1984b83`

    const whitelistFactoryContract = new web3.eth.Contract('', `0xc407722d150c8a65e890096869f8015D90a89EfD`, {
      from: '0x188eeC07287D876a23565c3c568cbE0bb1984b83', // default from address
      gasPrice: '20000000000',
    })
    console.log(whitelistFactoryContract.defaultChain, Date.now())
    await whitelistFactoryContract.methods
      .updateWhitelist(web3.utils.utf8ToBytes(1), `q1q1q1q1`, false)
      .send()
      .then((res) => {
        console.log(res)
      })
  }

  const createWhitelist = async () => {
    console.log(auth.wallet)
    web3.eth.defaultAccount = auth.wallet

    const whitelistFactoryContract = new web3.eth.Contract(ABI, import.meta.env.VITE_WHITELISTFACTORY_CONTRACT_MAINNET)
    await whitelistFactoryContract.methods
      .addWhitelist(``, Date.now(), 1710102205873, `0x0D5C8B7cC12eD8486E1E0147CC0c3395739F138d`, [])
      .send({ from: auth.wallet })
      .then((res) => {
        console.log(res)
      })
  }

  const handleSearch = async () => {
    let dataFilter = app
    if (txtSearchRef.current.value !== '') {
      let filteredData = dataFilter.filter((item) => item.name.toLowerCase().includes(txtSearchRef.current.value.toLowerCase()))
      if (filteredData.length > 0) setApp(filteredData)
    } else setApp(backApp)
  }

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

    return false
  }

  const getAppList = async () => {
    let web3 = new Web3(`https://rpc.lukso.gateway.fm`)
    web3.eth.defaultAccount = auth.wallet
    const UpstoreContract = new web3.eth.Contract(ABI, import.meta.env.VITE_UPSTORE_CONTRACT_MAINNET)
    return await UpstoreContract.methods.getAppList().call()
  }

  const getLike = async (appId) => {
    let web3 = new Web3(import.meta.env.VITE_RPC_URL)
    const UpstoreContract = new web3.eth.Contract(ABI, import.meta.env.VITE_UPSTORE_CONTRACT_MAINNET)
    return await UpstoreContract.methods.getLikeTotal(appId).call()
  }

  const handleRemoveRecentApp = async (e, appId) => {
    localStorage.setItem('appSeen', JSON.stringify(recentApp.filter((reduceItem) => reduceItem.appId !== appId)))
    
    // Refresh the recent app list
    getRecentApp().then((res) => {
      setRecentApp(res)
    })
  }

  const getRecentApp = async () => {
    return await JSON.parse(localStorage.getItem(`appSeen`))
  }
  useEffect(() => {
    // /0xd0f34b10
    //console.log('-------------',web3.eth.abi.encodeFunctionSignature(`getAppList()`))
    getAppList().then(async (res) => {
      const responses = await Promise.all(res.map(async (item) => Object.assign(await fetchIPFS(item.metadata), item, { like: web3.utils.toNumber(await getLike(item.id)) })))
      setApp(responses)
      setBackupApp(responses)
      setIsLoading(false)
    })

    getRecentApp().then((res) => {
      setRecentApp(res)
    })
  }, [])

  return (
    <>
      <section className={styles.section}>
        <div className={`__container`} data-width={`large`}>
          <figure className={`${styles['logo']} ms-motion-slideDownIn`}>
            <img alt={import.meta.env.VITE_NAME} src={Logo} />
            <figcaption>UP STORE</figcaption>
          </figure>

          <div className={`${styles['txt-search']}`}>
            <div className={styles['access-key']}>
              <span>Alt</span>
              <span>+</span>
              <span>Shift</span>
              <span>+</span>
              <span>U</span>
            </div>
            <input type={`text`} placeholder={`Search in ${app && app.length} dapps`} list={`apps`} accessKey={`u`} onChange={() => handleSearch()} ref={txtSearchRef} />
          </div>

          <datalist id={`apps`}>{app && app.map((item, i) => <option key={i} value={item.name} />)}</datalist>

          <div className={`d-flex flex-row align-items-center justify-content-start `}>
            <MaterialIcon name={`local_fire_department`} style={{ color: 'var(--color-primary)' }} />
            <span>Hot dApps</span>
          </div>

          <div className={`${styles['grid']} grid grid--fit mt-10`} style={{ '--data-width': '85px' }}>
            {isLoading && (
              <>
                {[1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1].map((item, i) => (
                  <Shimmer key={i}>
                    <div style={{ width: `50px`, height: `85px` }} />
                  </Shimmer>
                ))}
              </>
            )}

            {app &&
              app.length > 0 &&
              app
                .filter((item, i) => item.status && i < 20)
                .sort((a, b) => b.like - a.like)
                .map((item, i) => (
                  <div key={i} className={`${styles['grid__item']} d-flex flex-column align-items-center`} style={{ rowGap: '.6rem' }}>
                    <Link
                      to={`${item.id}`}
                      style={{ backgroundColor: item.style && JSON.parse(item.style).backgroundColor }}
                      className={`d-flex flex-column align-items-center justify-content-center animate pop`}
                    >
                      <figure title={item.name}>
                        <img alt={item.name} src={item.logo} />
                      </figure>
                    </Link>
                    <span>{item.name}</span>
                  </div>
                ))}

            {app && app.length > 0 && <DefaultAppHolder app={app.filter((item) => item.status)} />}
          </div>

          <p className="mt-50">Recent dApps</p>

          {recentApp && recentApp.length > 0 && (
            <>
              <div className={`${styles['grid']} grid grid--fill mt-10`} style={{ '--data-width': '85px' }}>
                {app &&
                  app.length > 0 &&
                  app
                    .filter((item, i) => recentApp.find((appSeenItem) => appSeenItem.appId === item.id) !== undefined)
                    .reverse()
                    .map((item, i) => (
                      <div key={i} className={`${styles['grid__item']} d-flex flex-column align-items-center`} style={{ rowGap: '.6rem' }}>
                        <div className={`${styles['close-btn']}`} onClick={(e) => handleRemoveRecentApp(e, item.id)}>
                          <i className={`ms-Icon ms-Icon--ChromeClose`} aria-hidden="true"></i>
                        </div>
                        <Link
                          to={`${item.id}`}
                          style={{ backgroundColor: item.style && JSON.parse(item.style).backgroundColor }}
                          className={`d-flex flex-column align-items-center justify-content-center animate pop`}
                        >
                          <figure title={item.name}>
                            <img alt={item.name} src={item.logo} />
                          </figure>
                        </Link>
                        <span>{item.name}</span>
                      </div>
                    ))}
              </div>
            </>
          )}
        </div>

        <div className={styles['statistics']}>
          <div className={`__container`} data-width={`medium`}>
            <h6>There is much more to explore</h6>
            <p>
              Unlock a world of possibilities with Lukso's extensive range of decentralized applications. With countless options available, you can explore and experience the full potential of
              blockchain technology like never before. Start your journey today and discover what Lukso has to offer.
            </p>
            <div className={`${styles['grid']} grid grid--fill mt-60`} style={{ '--data-width': '150px' }}>
              <div className={`${styles['statistics__card']} card d-flex flex-column`}>
                <span>{app && app.length > 0 && app.length}</span>
                <small>Dapps</small>
              </div>
              <div className={`${styles['statistics__card']} card d-flex flex-column`}>
                <span>{app && app.length > 0 && app.filter((item) => item.category === 'NFT').length}</span>
                <small>NFT Collections</small>
              </div>
              <div className={`${styles['statistics__card']} card d-flex flex-column`}>
                <span>{1}</span>
                <small>Chains</small>
              </div>
            </div>
          </div>
        </div>
      </section>
    </>
  )
}

const DefaultAppHolder = ({ app }) => {
  let holder = []
  if (app.length > 20) return
  for (let i = 0; i < 20 - app.length; i++) {
    holder.push(
      <div key={i} className={`${styles['grid__item']} d-flex flex-column align-items-center`} style={{ rowGap: '.6rem' }}>
        <a
          href={`#`}
          onClick={() => toast(`Submit your dapp to the manager`)}
          style={{ backgroundColor: '#FFF1F8' }}
          className={`${styles['grid__item']} d-flex flex-column align-items-center justify-content-center animate pop`}
        >
          <figure>
            <img alt={import.meta.VITE_NAME} src={DappDefaultIcon} />
          </figure>
        </a>
        <span>Unnamed</span>
      </div>
    )
  }
  return holder
}

export default Home
