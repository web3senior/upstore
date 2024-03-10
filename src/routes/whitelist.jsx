import { useEffect, useRef, useState } from 'react'
import { useNavigate } from 'react-router-dom'
import { Title } from './helper/DocumentTitle'
import MaterialIcon from './helper/MaterialIcon'
import { useAuth, web3, _ } from '../contexts/AuthContext'
import DefaultProfile from './../assets/default-profile.svg'
import algoliasearch from 'algoliasearch'
import Web3 from 'web3'
import Shimmer from './helper/Shimmer'
import ABI from './../abi/upstore.json'
import styles from './Whitelist.module.scss'

const WhitelistFactoryAddr = web3.utils.padLeft(`0x2`, 64)

const client = algoliasearch(import.meta.env.VITE_APPLICATION_ID, import.meta.env.VITE_API_KEY)
//prod_testnet_universal_profiles
const index = client.initIndex('prod_mainnet_universal_profiles')
let hits = []

function Whitelist({ title }) {
  Title(title)
  const [isLoading, setIsLoading] = useState(true)
  const [whitelist, setWhitelist] = useState([])
  const [profile, setProfile] = useState([])
  const auth = useAuth()
  const navigate = useNavigate()
  const timerRef = useRef()

  const readProfile = async (addr) => {
    const myHeaders = new Headers()
    myHeaders.append('Content-Type', 'application/json')

    const raw = JSON.stringify({
      jsonrpc: '2.0',
      method: 'eth_call',
      params: [
        {
          to: addr,
          data: '0x54f6127f5ef83ad9559033e6e941db7d7c495acdce616347d28e90c7ce47cbfcfcad3bc5',
        },
        'latest',
      ],
    })

    const requestOptions = {
      method: 'POST',
      headers: myHeaders,
      body: raw,
      redirect: 'follow',
    }

    await fetch(`https://rpc.lukso.gateway.fm`, requestOptions)
      .then((response) => response.json())
      .then(async (data) => {
        const hex = data.result.substr(210, 106)
        let bytes = [],
          str

        for (var i = 0; i < hex.length - 1; i += 2) {
          bytes.push(parseInt(hex.substr(i, 2), 16))
        }

        str = String.fromCharCode.apply(String, bytes).replace('ipfs://', '').replace('://', '').replace(/[^\w]/g, "").trim()

        const response = await fetch(`https://api.universalprofile.cloud/ipfs/${encodeURI(str)}`)
        if (!response.ok) throw new Response('Failed to get data', { status: 500 })
        const json = await response.json()
        console.log(json)
        if (typeof json === 'object') setProfile((oldProfile) => [...oldProfile, { addr: addr, data: json }])
      })
      .catch((error) => {
        console.error(error)
        setProfile((oldProfile) => [...oldProfile, { addr: addr, data: { LSP3Profile: { name: 'anonymous', profileImage: [] } } }])
      })
  }

  const fetchWhitelist = async () => {
    setProfile([])
    let web3 = new Web3(`https://rpc.lukso.gateway.fm`)
    const whitelistFactoryContract = new web3.eth.Contract(ABI, import.meta.env.VITE_WHITELISTFACTORY_CONTRACT_MAINNET)
    return await whitelistFactoryContract.methods
      .getUserList(WhitelistFactoryAddr)
      .call()
      .then((res) => {
        // console.log(res)
        if (res.length >= 1) {
          setWhitelist(res)
          res.map(async (item) => await readProfile(item))
          setIsLoading(false)
        }

        return res
      })
  }

  const decodeProfileImage = (data) => {
    let url
    if (data.LSP3Profile.profileImage && data.LSP3Profile.profileImage.length > 0) {
      if (data.LSP3Profile.profileImage[0].url.indexOf(`ipfs`) > -1) return `https://api.universalprofile.cloud/ipfs/${data.LSP3Profile.profileImage[0].url.replace('ipfs://', '')}`
      else return `${data.LSP3Profile.profileImage[0].url}`
    } else url = DefaultProfile
    return url
  }

  const handleRefresh = () => {
    setIsLoading(!isLoading)
    fetchWhitelist()
  }

  useEffect(() => {
    fetchWhitelist()
  }, [])
  return (
    <>
      <section className={styles.section}>
        <div className={`__container`} data-width={`xlarge`}>
          <div className={styles['github-card']}>
            <div className={styles['github-card__body']}>
              <p>Our "whitelist" is a list of Universal Profile users who are granted early access for minting the universal fam token.</p>
              <p className="mt-10" style={{ textDecoration: 'underline' }}>
                <a href={`https://explorer.execution.mainnet.lukso.network/address/${import.meta.env.VITE_WHITELISTFACTORY_CONTRACT_MAINNET}`} target={`_blank`}>
                  <b>Whitelist Factory Contract on Lukso</b>
                </a>
              </p>
            </div>
          </div>

          <div className={`card mt-40`}>
            <div className={`card__header d-flex align-items-center justify-content-between`}>
              Total Universal Profile {!isLoading && profile && <>({profile.length})</>}
              <button className={styles['btn-refresh']} onClick={() => handleRefresh()}>
                <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M18 33C9.71573 33 3 26.2843 3 18C3 17.5175 3.02279 17.0403 3.06734 16.5694C3.1446 15.7528 2.53304 15 1.71277 15C0.867802 15 0.137352 15.61 0.0656395 16.452C0.0221786 16.9622 0 17.4785 0 18C0 27.9411 8.05887 36 18 36C27.9411 36 36 27.9411 36 18C36 8.05887 27.9411 0 18 0C13.2682 0 8.96284 1.82581 5.75 4.81136V2.25C5.75 1.42157 5.07843 0.75 4.25 0.75C3.42157 0.75 2.75 1.42157 2.75 2.25V8.75C2.75 9.57843 3.42157 10.25 4.25 10.25H10.75C11.5784 10.25 12.25 9.57843 12.25 8.75C12.25 7.92157 11.5784 7.25 10.75 7.25H7.53875C10.241 4.61993 13.9313 3 18 3C26.2843 3 33 9.71573 33 18C33 26.2843 26.2843 33 18 33Z"
                    fill="#242424"
                  />
                </svg>
              </button>
            </div>
            <div className={`card__body`}>
              <div className={`${styles['grid']} grid grid--fit`} style={{ '--data-width': '124px' }}>
                {isLoading && (
                  <>
                    {[1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1].map((item, i) => (
                      <Shimmer key={i}>
                        <div style={{ width: `124px`, height: `124px` }} />
                      </Shimmer>
                    ))}
                  </>
                )}

                {profile &&
                  profile.map((item, i) => (
                    <div className={`${styles['grid__item']} d-flex flex-column align-items-center justify-content-center animate pop`} key={i}>
                      <figure className={`${styles['pfp']} d-flex flex-row align-items-center`}>
                        <img alt={`The Universal Fam NFT Collection`} src={decodeProfileImage(item.data)} />
                      </figure>

                      <b>@{item.data.LSP3Profile.name ? item.data.LSP3Profile.name : 'unnamed'}</b>

                      <a className={`text-primary d-flex align-items-center`} target={`_blank`} href={`https://wallet.universalprofile.cloud/${item.addr}?referrer=universal-family&network=mainnet`}>
                        {`${item.addr.slice(0, 4)}...${item.addr.slice(38)}`}
                      </a>
                    </div>
                  ))}

              </div>
            </div>
          </div>
        </div>
      </section>
    </>
  )
}

export default Whitelist
