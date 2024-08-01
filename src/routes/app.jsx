import { useEffect, useRef, useState } from 'react'
import { useNavigate, defer, useParams } from 'react-router-dom'
import { Title } from './helper/DocumentTitle'
import MaterialIcon from './helper/MaterialIcon'
import Shimmer from './helper/Shimmer'

import Loading from './components/LoadingSpinner'

import { CheckIcon, ChromeIcon, BraveIcon } from './components/icons'
import toast, { Toaster } from 'react-hot-toast'
import { useAuth, web3, contract, donationContract } from '../contexts/AuthContext'
import styles from './App.module.scss'
import PinkCheckmark from './../../src/assets/verified.svg'
import GitHubMark from './../../src/assets/icon-github.svg'
import IconX from './../../src/assets/icon-x.svg'
import IconCG from './../../src/assets/icon-cg.svg'
import IconTelegram from './../../src/assets/icon-telegram.svg'
import IconDiscord from './../../src/assets/icon-discord.svg'
import ABI_DONATION_LUKSO from './../abi/donation_lukso.json'
import Banner from './../../src/assets/banner.png'
import LSP0ERC725Account from '@lukso/lsp-smart-contracts/artifacts/LSP0ERC725Account.json'
import LSP7Mintable from '@lukso/lsp-smart-contracts/artifacts/LSP7Mintable.json'
import Web3 from 'web3'
import ABI from '../abi/upstore.json'
import party from 'party-js'
// import { getApp } from './../util/api'
import DappDefaultIcon from './../assets/dapp-default-icon.svg'
import { Doughnut } from 'react-chartjs-2'
const options = {
  layout: {
    padding: 10,
  },

  maintainAspectRatio: false,
  responsive: true,
  plugins: {
    legend: {
      position: 'top',
      labels: {
        font: {
          size: 13,
          family: 'Vazir',
        },
      },
    },
  },
}
const getOrCreateLegendList = (chart, id) => {
  const legendContainer = document.getElementById(id)
  let listContainer = legendContainer.querySelector('ul')

  if (!listContainer) {
    listContainer = document.createElement('ul')
    listContainer.style.display = 'flex'
    listContainer.style.flexDirection = 'row'
    listContainer.style.margin = 0
    listContainer.style.padding = 0

    legendContainer.appendChild(listContainer)
  }

  return listContainer
}

const htmlLegendPlugin = {
  id: 'htmlLegend',
  afterUpdate(chart, args, options) {
    const ul = getOrCreateLegendList(chart, options.containerID)

    // Remove old legend items
    while (ul.firstChild) {
      ul.firstChild.remove()
    }

    // Reuse the built-in legendItems generator
    const items = chart.options.plugins.legend.labels.generateLabels(chart)

    items.forEach((item) => {
      const li = document.createElement('li')
      li.style.alignItems = 'center'
      li.style.cursor = 'pointer'
      li.style.display = 'flex'
      li.style.flexDirection = 'row'
      li.style.marginLeft = '10px'

      li.onclick = () => {
        const { type } = chart.config
        if (type === 'pie' || type === 'doughnut') {
          // Pie and doughnut charts only have a single dataset and visibility is per item
          chart.toggleDataVisibility(item.index)
        } else {
          chart.setDatasetVisibility(item.datasetIndex, !chart.isDatasetVisible(item.datasetIndex))
        }
        chart.update()
      }

      // Color box
      const boxSpan = document.createElement('span')
      boxSpan.style.background = item.fillStyle
      boxSpan.style.borderColor = item.strokeStyle
      boxSpan.style.borderWidth = item.lineWidth + 'px'
      boxSpan.style.display = 'inline-block'
      boxSpan.style.flexShrink = 0
      boxSpan.style.height = '20px'
      boxSpan.style.marginRight = '10px'
      boxSpan.style.width = '20px'

      // Text
      const textContainer = document.createElement('p')
      textContainer.style.color = item.fontColor
      textContainer.style.margin = 0
      textContainer.style.padding = 0
      textContainer.style.textDecoration = item.hidden ? 'line-through' : ''

      const text = document.createTextNode(item.text)
      textContainer.appendChild(text)

      li.appendChild(boxSpan)
      li.appendChild(textContainer)
      ul.appendChild(li)
    })
  },
}
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
  const [tokenList, setTokenList] = useState()
  const [donationList, setDonationList] = useState()
  const [showApprove, setShowApprove] = useState(false)
  const [chartData, setChartData] = useState([])
  const auth = useAuth()
  const navigate = useNavigate()
  const params = useParams()
  const chartRef = useRef()

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
  const getDonationList = async () => {
    const web3 = new Web3(window.lukso)
    const donationContract1 = new web3.eth.Contract(ABI_DONATION_LUKSO, import.meta.env.VITE_DONATION_CONTRACT_MAINNET_LUKSO)
    return await donationContract1.methods.getDonationList().call()
  }
  const getTokenList = async () => {
    const web3 = new Web3(window.lukso)
    const donationContract1 = new web3.eth.Contract(ABI_DONATION_LUKSO, import.meta.env.VITE_DONATION_CONTRACT_MAINNET_LUKSO)
    return await donationContract1.methods.getTokenList().call()
  }

  const getLSP7Data = async (tokenContract) => {
    const web3 = new Web3(window.lukso)
    const donationContract1 = new web3.eth.Contract(LSP7Mintable.abi, tokenContract)
    return await donationContract1.methods.getData(`0xdeba1e292f8ba88238e10ab3c7f88bd4be4fac56cad5194b6ecceaf653468af1`).call()
  }

  const handleDonate = async (to) => {
    // if (!auth.wallet) {
    //   toast.error(`Please connect wallet`)
    //   return
    // }

    const t = toast.loading(`Waiting for transaction's confirmation`)

    const web3 = new Web3(window.lukso)
    const donationContract1 = new web3.eth.Contract(ABI_DONATION_LUKSO, import.meta.env.VITE_DONATION_CONTRACT_MAINNET_LUKSO)
    let accounts = await web3.eth.getAccounts()
    if (accounts.length === 0) await web3.eth.requestAccounts()
    accounts = await web3.eth.getAccounts()

    try {
      donationContract1.methods
        .donate(
          `${to}`, // to
          web3.utils.toWei(document.querySelector(`[name="amount"]`).value, `ether`), //amount
          true, //force
          '0x', //data
          document.querySelector(`[name="token"]`).value //tokenId
        )
        .send({ from: accounts[0], value: web3.utils.toWei(document.querySelector(`[name="amount"]`).value, `ether`) })
        .then((res) => {
          console.log(res)
          toast.dismiss(t)

          // Party
          party.confetti(document.querySelector(`.party-holder`), {
            count: party.variation.range(20, 40),
            shapes: ['star', 'roundedSquare'],
          })

          window.location.reload()
        })
    } catch (error) {
      console.error(error)
      toast.dismiss(t)
    }
  }

  const handleApprove = async () => {
    // if (!auth.wallet) {
    //   toast.error(`Please connect wallet`)
    //   return
    // }
    const t = toast.loading(`Waiting for transaction's confirmation`)

    const web3 = new Web3(window.lukso)
    let accounts = await web3.eth.getAccounts()
    if (accounts.length === 0) await web3.eth.requestAccounts()
    accounts = await web3.eth.getAccounts()

    console.log(tokenList.filter((item) => item.id === document.querySelector(`[name="token"]`).value)[0].addr)

    const lsp7Contract = new web3.eth.Contract(LSP7Mintable.abi, `0x39F73B9C8D4E370fD9ff22C932eD58009680aff0`)

    return await lsp7Contract.methods
      .authorizeOperator(`${import.meta.env.VITE_DONATION_CONTRACT_MAINNET_LUKSO}`, web3.utils.toWei(document.querySelector(`[name="amount"]`).value, `ether`), '0x')
      .send({ from: accounts[0], value: 0 })
      .then(() => {
        try {
          const donationContract1 = new web3.eth.Contract(ABI_DONATION_LUKSO, import.meta.env.VITE_DONATION_CONTRACT_MAINNET_LUKSO)
          donationContract1.methods
            .donate(
              `0xc1A411B2F0332C86c90Af22f5367A0265bCB1Df9`, // to
              web3.utils.toWei(document.querySelector(`[name="amount"]`).value, `ether`), //amount
              true, //force
              '0x', //data
              document.querySelector(`[name="token"]`).value //tokenId
            )
            .send({ from: accounts[0], value: 0 })
            .then((res) => {
              console.log(res)

              // Party
              party.confetti(document.querySelector(`.party-holder`), {
                count: party.variation.range(20, 40),
                shapes: ['star', 'roundedSquare'],
              })

              toast.dismiss(t)
            })
        } catch (error) {
          console.error(error)
          toast.dismiss(t)
        }
      })
  }

  const getRandomColor = () => {
    var letters = '0123456789ABCDEF'
    var color = '#'
    for (var i = 0; i < 6; i++) {
      color += letters[Math.floor(Math.random() * 16)]
    }
    return `${color}`
  }
  const getApp = async () => await contract.methods.getApp(params.appId).call()
  const getLike = async () => await contract.methods.getLikeTotal(params.appId).call()
  const getToken = async (tokenId) => await donationContract.methods.token(tokenId).call()
  const handleLike = async () => {
    if (!auth.wallet) {
      toast.error(`Please connect wallet`)
      return
    }

    const t = toast.loading(`Waiting for transaction's confirmation`)

    try {
      web3.eth.defaultAccount = auth.wallet

      return await contract.methods
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

  const converTimestamp = (unix_timestamp) => {
    const date = new Date(unix_timestamp * 1000)
    const fullYear = date.getFullYear() // prints the year (e.g. 2021)
    const month = date.getMonth() // prints the month (0-11, where 0 = January)
    const day = date.getDate() // prints the day of the month (1-31)
    const hours = date.getHours() // prints the hour (0-23)
    const minutes = date.getMinutes() // prints the minute (0-59)
    const seconds = date.getSeconds() // prints the second (0-59)
    const formattedTime = `${day}/${month}/${fullYear} ` + hours + ':' + minutes + ':' + seconds
    return formattedTime
  }

  const decodeProfileImage = (data) => {
    console.log(data)
    let url
    if (data.LSP3Profile.profileImage && data.LSP3Profile.profileImage.length > 0) {
      if (data.LSP3Profile.profileImage[0].url.indexOf(`ipfs`) > -1) return `${import.meta.env.VITE_IPFS_GATEWAY}${data.LSP3Profile.profileImage[0].url.replace('ipfs://', '')}`
      else return `${data.LSP3Profile.profileImage[0].url}`
    } else url = DefaultProfile
    return url
  }

  useEffect(() => {
    getApp().then(async (res) => {
      console.log(res)
      if (!res.status) return

      let data = res
      await fetchIPFS(res.metadata).then(async (IPFSres) => {
        data = Object.assign(data, IPFSres)

        await auth.fetchProfile(res.manager).then((res) => {
          data.managerInfo = res.LSP3Profile
        })

        setApp([data])
        setIsLoading(false)
      })
    })

    getLike().then((res) => {
      setLike(web3.utils.toNumber(res))
    })

    getTokenList().then((res) => {
      console.log(res)
      let data = res
      res.map((item, i) => {
        if (item.addr === '0x0000000000000000000000000000000000000000') {
          data[i].name = `LYX`
        } else {
          getLSP7Data(item.addr).then((res) => {
            console.log(web3.utils.hexToUtf8(res))
            data[i].name = web3.utils.hexToUtf8(res)
          })
        }
      })
      setTokenList(data)
    })

    getDonationList(auth.wallet).then(async (res) => {
      console.log(res)
      if (res.length < 1) return

      let data = res
      let chartDataLocal = {
        key: [],
        value: [],
      }

      // Fetch and concat the token name
      const tokenResponses = await Promise.all(res.map(async (item) => await getToken(item.tokenId)))
      tokenResponses.map(async (item, i) => {
        if (item.addr !== `0x0000000000000000000000000000000000000000`) item.name = web3.utils.hexToUtf8(await getLSP7Data(item.addr))
        data[i].tokenInfo = item
      })

      // Fetch and concat the user profile
      const responses = await Promise.all(res.map(async (item) => await auth.fetchProfile(item.donator)))
      responses.map(async (item, i) => {
        data[i].profile = item

        chartDataLocal.key[i] = item.LSP3Profile.name
        chartDataLocal.value[i] = web3.utils.fromWei(data[i].amount, `ether`)
      })

      setChartData(chartDataLocal)
      setDonationList(data)
      console.log(data)
      setIsLoading(false)
    })
  }, [])

  return (
    <>
      <section className={`${styles.section} s-motion-slideUpIn`}>
        <div className={`__container`} data-width={`medium`}>
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
              <div className={`ms-Grid-col ms-sm12 ms-md12 ms-lg12`}>
                {app && app.length > 0 && (
                  <>
                    <div className="grid grid--fit" style={{ '--data-width': `200px`, gap: `1rem` }}>
                      <div className={`card`} style={{ height: `200px` }}>
                        <div className={`card__body`} style={{ backgroundColor: app[0].style && JSON.parse(app[0].style).backgroundColor }}>
                          {app.map((item, i) => (
                            <div className={`party-holder`} key={i}>
                              <div
                                style={{ backgroundColor: item.style && JSON.parse(item.style).backgroundColor }}
                                className={`d-flex flex-column align-items-center justify-content-center animate fade`}
                                key={i}
                              >
                                <figure className={`${styles['logo']}`} title={item.category}>
                                  <img alt={item.name} src={item.logo} />
                                  <figcaption>
                                    {item.name}
                                    <img src={PinkCheckmark} />
                                  </figcaption>
                                </figure>
                                <p>
                                  <span className={`badge badge-pill badge-warning`}>#{app[0].category}</span>
                                </p>
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
                        </div>
                      </div>

                      <div className={`card`}>
                        <div className={`card__body animate fade d-flex flex-column align-items-center`}>
                          <figure className={`${styles['owner']}`}>
                            <img alt={``} src={`${import.meta.env.VITE_IPFS_GATEWAY}${app[0].managerInfo?.profileImage[0]?.url.replace('ipfs://', '').replace('://', '')}`} />
                            <figcaption>@{app[0]?.name}</figcaption>
                          </figure>
                          <p title={app[0].manager}>{`${app[0].manager.slice(0, 6)}...${app[0].manager.slice(38)}`}</p>
                          <p>
                            <a target={`_blank`} href={`https://wallet.universalprofile.cloud/${app[0].manager}?referrer=UPStore&network=mainnet`}>
                              View Owner
                            </a>
                          </p>
                        </div>
                      </div>
                    </div>

                    <div className={`card mt-10`}>
                      <div className={`card__body animate fade`}>{app[0].description}</div>
                    </div>

                    <div className={`card mt-10`}>
                      <div className={`card__body animate fade`}>
                        {app && app.length > 0 && app[0].tags && (
                          <div className={`d-flex`}>
                            <span>🔒</span>
                            <span>{app[0].url}</span>
                          </div>
                        )}
                      </div>
                    </div>

                    {app[0].repo && (
                      <>
                        <div className={`card ${styles['repo']} mt-10`}>
                          <div className={`card__body animate fade`}>
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

                    <div className={`${styles.donator} card mb-40`}>
                      <div className={`card__header`}>Donotors</div>
                      <div className={`card__body`}>
                        <table className={`data-table`}>
                          <caption>Donators list</caption>
                          <thead>
                            <tr>
                              <th scope="col" className={`text-left`}>
                                Donotar
                              </th>
                              <th scope="col">Amount</th>
                              <th scope="col">Token</th>
                              <th scope="col">Date</th>
                            </tr>
                          </thead>
                          <tbody>
                            {donationList &&
                              donationList.length > 0 &&
                              donationList.map((item, i) => {
                                return (
                                  <tr key={i} className={`text-center`}>
                                    <td>
                                      {item.profile && (
                                        <figure
                                          className={`${styles['pfp']} d-flex flex-row align-items-center`}
                                          style={{ '--animate-duration': `.${i * 2}s` }}
                                          title={`${item.profile.LSP3Profile.name}`}
                                        >
                                          <img alt={`${item.profile.LSP3Profile.name}`} src={decodeProfileImage(item.profile)} style={{ width: '48px', borderRadius: '999px' }} draggable="true" />
                                        </figure>
                                      )}
                                    </td>
                                    <td>{web3.utils.fromWei(web3.utils.toNumber(item.amount), `ether`)}</td>
                                    <td>{item.tokenInfo.name ? `$${item.tokenInfo.name}` : `⏣LYX`}</td>
                                    <td>{converTimestamp(web3.utils.toNumber(item.dt))}</td>
                                  </tr>
                                )
                              })}
                          </tbody>
                        </table>

                        {chartData && chartData.key && chartData.key.length > 0 && (
                          <div style={{ height: '300px' }}>
                            <Doughnut
                              width={'30%'}
                              options={options}
                              plugins={htmlLegendPlugin}
                              data={{
                                labels: chartData.key,
                                datasets: [
                                  {
                                    label: 'Amount:',
                                    data: chartData.value,
                                    backgroundColor: chartData.key.map(() => getRandomColor() + `45`),
                                    borderColor: 'rgba(2,2,2,.25)',
                                    borderWidth: 2,
                                  },
                                ],
                              }}
                            />
                          </div>
                        )}
                      </div>
                    </div>

                    <div className={`card mt-20 form`}>
                      <div className={`card__header`}>Direct donation</div>
                      <div className={`card__body d-flex flex-column`} style={{ rowGap: `.5rem` }}>
                        <div className={`alert alert--info`}>
                          A small 2% fee is applied to each donation to support the platform. Your generosity helps maintain this direct donation system and allows us to continue providing this
                          service. Thank you for your support!
                        </div>
                        <div className={`flex-1 d-flex flex-column`}>
                          <label htmlFor="token">Token</label>
                          <select
                            name="token"
                            id="token"
                            onChange={(e) => {
                              if (e.target.value !== `0x0000000000000000000000000000000000000000000000000000000000000001`) {
                                setShowApprove(true)
                              } else {
                                setShowApprove(false)
                              }
                            }}
                          >
                            {tokenList &&
                              tokenList.length > 0 &&
                              tokenList.map((item, i) => (
                                <option key={i} value={item.id}>
                                  ${item.name}
                                </option>
                              ))}
                          </select>
                        </div>

                        <div className={`flex-1 d-flex flex-column`}>
                          <label htmlFor="amount">Amount</label>
                          <input type="number" min={1} name="amount" id="" defaultValue={1} />
                        </div>

                        {!showApprove && (
                          <button className="btn" onClick={() => handleDonate(app[0].manager)}>
                            Donate
                          </button>
                        )}
                        {showApprove && (
                          <button className="btn" onClick={() => handleApprove()}>
                            Approve
                          </button>
                        )}
                      </div>
                    </div>

                    <div className={`${styles['like']} d-flex align-items-center mt-40 mb-40`}>
                      <span>Like this DApp?</span>

                      <button className={`${styles['btn-like']}`} onClick={() => handleLike()}>
                        <MaterialIcon name={`favorite`} />
                      </button>

                      <span>{like}</span>
                    </div>
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
