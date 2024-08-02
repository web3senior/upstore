import { Suspense, useState, useEffect, useRef } from 'react'
import { useLoaderData, defer, Form, Await, useRouteError, Link, useNavigate } from 'react-router-dom'
import { Title } from './helper/DocumentTitle'
import MaterialIcon from './helper/MaterialIcon'
import Shimmer from './helper/Shimmer'
import toast, { Toaster } from 'react-hot-toast'
import { useAuth, web3, contract, donationContract } from '../contexts/AuthContext'
import ABI_DONATION_LUKSO from './../abi/donation_lukso.json'
import party from 'party-js'
import styles from './Home.module.scss'
import Web3 from 'web3'

export const loader = async () => {
  return defer({ key: 'val' })
}

function Admin({ title }) {
  Title(title)
  const [loaderData, setLoaderData] = useState(useLoaderData())
  const [isLoading, setIsLoading] = useState(true)
  const [teamMintCounter, setTeamMintCounter] = useState(0)

  const [candySecondaryColor, setCandySecondaryColor] = useState('#0E852E')
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

  const getRecordType = async () => await contract.methods.getBalance().call()
  const handlePause = async () => await contract.methods.pause().send()
  const getTotalSupply = async () => await contract.methods.totalSupply().call()

  const getCouncilMintExpiration = async () => await contract.methods.councilMintExpiration().call()

  const getTeamMintCounter = async () => await contract.methods.teamMintCounter().call()

  const handleAddToken = async (e) => {
    const t = toast.loading(`Waiting for transaction's confirmation`)
    const web3 = new Web3(window.lukso)

    let accounts = await web3.eth.getAccounts()
    if (accounts.length === 0) await web3.eth.requestAccounts()
    accounts = await web3.eth.getAccounts()

    console.log(document.querySelector(`[name="token"]`).value)
    try {
      donationContract.methods
        .addToken(document.querySelector(`[name="token"]`).value)
        .send({ from: accounts[0], value: 0 })
        .then((res) => {
          console.log(res)
          toast.dismiss(t)

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

  const handleDelete = async (e) => {
    const t = toast.loading(`Waiting for transaction's confirmation`)
    const web3 = new Web3(window.lukso)

    let accounts = await web3.eth.getAccounts()
    if (accounts.length === 0) await web3.eth.requestAccounts()
    accounts = await web3.eth.getAccounts()

    try {
      donationContract.methods
        .deleteToken(document.querySelector(`[name="token"]`).value)
        .send({ from: accounts[0], value: 0 })
        .then((res) => {
          console.log(res)
          toast.dismiss(t)

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

  const handleWithdraw = async () =>
    await donationContract.methods.withdraw().send({
      from: auth.wallet,
    })

  const handleUpdateRecordType = () => {}

  useEffect(() => {
    getRecordType().then(async (res) => {
      console.log(res)
      setRecordType(res)
      setIsLoading(false)
    })
  }, [])

  return (
    <>
      <section className={`${styles.section} ms-motion-slideDownIn`}>
        <div className={`${styles['__container']} __container`} data-width={`medium`}>
          <div className={`card`}>
            <div className={`card__header`}>Add LSP7 - donation</div>
            <div className={`card__body form`}>
              <input type="text" name="token" id="token" />
              <button className="btn mt-10" onClick={(e) => handleAddToken(e)}>
                Add token
              </button>
              <button className="btn mt-10" onClick={(e) => handleDelete(e)}>
                remove token
              </button>
            </div>
          </div>

          <div className={`card mt-10`}>
            <div className={`card__header`}>Transfer ownership</div>
            <div className={`card__body form`}>
              <div>
                <input className="input" type="text" id="newOwner" />
              </div>

              <button className="btn mt-10" onClick={(e) => handleTransfer(e)}>
                Transfer
              </button>
            </div>
          </div>

          <button onClick={() => handleWithdraw()}>Withdraw</button>
          <button onClick={() => handleUpdateRecordType()}>update RecordType</button>
          <Link to={`/`} className="btn mt-10" style={{ background: '#222' }}>
            Back
          </Link>
        </div>
      </section>
    </>
  )
}

export default Admin
