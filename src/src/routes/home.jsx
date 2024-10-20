import { Suspense, useState, useEffect, useRef } from 'react'
import { useLoaderData, defer, Form, Await, useRouteError, Link, useNavigate } from 'react-router-dom'
import { Title } from './helper/DocumentTitle'
import MaterialIcon from './helper/MaterialIcon'
import Shimmer from './helper/Shimmer'
import toast, { Toaster } from 'react-hot-toast'
import { useAuth, web3,contract } from './../contexts/AuthContext'
import Logo from './../../src/assets/logo.svg'
import Web3 from 'web3'
import ABI from './../abi/upstore.json'
import party from 'party-js'
import DappDefaultIcon from './../assets/dapp-default-icon.svg'
import styles from './Home.module.scss'

import { Chart as ChartJS, ArcElement, Tooltip, Legend } from 'chart.js'
import { Doughnut } from 'react-chartjs-2'
ChartJS.register(ArcElement, Tooltip, Legend)
export const data = {
  labels: ['Red', 'Orange', 'Yellow', 'Green', 'Blue'],
  datasets: [
    {
      label: '# of Votes',
      data: [12, 19, 3, 5, 2, 3],
      backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(255, 206, 86, 0.2)', 'rgba(75, 192, 192, 0.2)', 'rgba(153, 102, 255, 0.2)', 'rgba(255, 159, 64, 0.2)'],
      borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)'],
      borderWidth: 1,
    },
  ],
  options: {
    plugins: {
      title: {
        display: false,
        text: 'Custom Chart Title',
      },
    },
  },
}

party.resolvableShapes['UP'] = `<img src="http://localhost:5173/src/assets/up-logo.svg"/>`
party.resolvableShapes['Lukso'] = `<img src="http://localhost:5173/src/assets/lukso-logo.svg"/>`

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

  const getAppList = async () =>  await contract.methods.getAppList().call()

  const getLike = async (appId) => {
    let web3 = new Web3(import.meta.env.VITE_RPC_URL)
    return await contract.methods.getLikeTotal(appId).call()
  }

  const handleRemoveRecentApp = async (e, appId) => {
    localStorage.setItem('appSeen', JSON.stringify(recentApp.filter((reduceItem) => reduceItem.appId !== appId)))

    // Refresh the recent app list
    getRecentApp().then((res) => {
      setRecentApp(res)
    })
  }

  const getRecentApp = async () =>  await JSON.parse(localStorage.getItem(`appSeen`))
  

  useEffect(() => {
    getAppList().then(async (res) => {
      const responses = await Promise.all(res[0].map(async (item) => Object.assign(await fetchIPFS(item.metadata), item, { like: web3.utils.toNumber(await getLike(item.id)) })))
      setApp(responses.filter((item) => item.status))
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
            <figcaption>{import.meta.env.VITE_NAME}</figcaption>
          </figure>

          <div className={`${styles['txt-search']}`}>
            <div className={styles['access-key']}>
              <span>Alt</span>
              <span>+</span>
              <span>Shift</span>
              <span>+</span>
              <span>S</span>
            </div>
            <input type={`text`} placeholder={`Search`} list={`apps`} accessKey={`s`} onChange={() => handleSearch()} ref={txtSearchRef} />
          </div>

          <datalist id={`apps`}>{app && app.map((item, i) => <option key={i} value={item.name} />)}</datalist>

          <div className={`d-flex flex-row align-items-center justify-content-start `}>
            <MaterialIcon name={`local_fire_department`} style={{ color: 'var(--color-primary)' }} />
            <b className={`ms-fontSize-16`}>Hot DApps [{app && app.length}]</b>
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
                .filter((item, i) => item.status && i < 30)
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

          {recentApp && recentApp.length > 0 && (
            <>
              <p className="mt-50">Recent DApps</p>
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
      </section>
    </>
  )
}

const DefaultAppHolder = ({ app }) => {
  let holder = []
  if (app.length > 30) return
  for (let i = 0; i < 30 - app.length; i++) {
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
        <span>New DApp</span>
      </div>
    )
  }
  return holder
}

export default Home
