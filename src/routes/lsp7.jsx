import { useEffect, useRef, useState } from 'react'
import { useNavigate, defer, useParams, Form } from 'react-router-dom'
import { Title } from './helper/DocumentTitle'
import MaterialIcon from './helper/MaterialIcon'
import Shimmer from './helper/Shimmer'
import Loading from './components/LoadingSpinner'
import { CheckIcon, ChromeIcon, BraveIcon } from './components/icons'
import toast, { Toaster } from 'react-hot-toast'
import { useAuth, web3, _ } from '../contexts/AuthContext'
import styles from './Lsp7.module.scss'
import PinkCheckmark from './../../src/assets/verified.svg'
import GitHubMark from './../../src/assets/icon-github.svg'
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

  useEffect(() => {}, [])

  return (
    <>
      <section className={`${styles.section} s-motion-slideUpIn`}>
        <div className={`__container`} data-width={`large`}>
          <div className={`${styles['card']} mt-10`}>
            <div className={`${styles['card__body']} animate fade`}>
              <Form>
                <div>
                  <input type="text" name="" id="" />
                </div>
              </Form>
            </div>
          </div>
        </div>
      </section>
    </>
  )
}

export default App
