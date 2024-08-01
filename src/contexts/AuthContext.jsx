import React, { useContext, useEffect, useState } from 'react'
import { useNavigate, useLocation } from 'react-router-dom'
// import { user } from '../util/api'
// import { ERC725 } from '@erc725/erc725.js'
import LSP0ERC725Account from '@lukso/lsp-smart-contracts/artifacts/LSP0ERC725Account.json';
import ABI_LUKSO from './../abi/upstore.json'
import ABI_ARBITRUM from './../abi/upstore_arbitrum.json'
import ABI_DONATION from './../abi/donation_lukso.json'

// import lsp3ProfileSchema from '@erc725/erc725.js/schemas/LSP3ProfileMetadata.json'
import toast, { Toaster } from 'react-hot-toast'
import Web3 from 'web3'

export const PROVIDER = window.lukso ||import.meta.env.VITE_RPC_URL || window.ethereum
export const web3 = new Web3(PROVIDER)
export const contract = new web3.eth.Contract(ABI_LUKSO, import.meta.env.VITE_UPSTORE_CONTRACT_MAINNET_LUKSO)
export const donationContract = new web3.eth.Contract(ABI_DONATION, import.meta.env.VITE_DONATION_CONTRACT_MAINNET_LUKSO)
export const _ = web3.utils

export const AuthContext = React.createContext()
export function useAuth() {
  return useContext(AuthContext)
}

export const readBalance = async () => await web3.eth.getBalance(auth.wallet).then((balance) => web3.utils.fromWei(balance, 'ether').toString())
export const isAuth = async () => localStorage.getItem('accessToken')

export const chainID = async () => await web3.eth.getChainId()

export const getIPFS = async (CID) => {
  //  console.log(CID)
  let requestOptions = {
    method: 'GET',
    redirect: 'follow',
  }
  const response = await fetch(`https://api.universalprofile.cloud/ipfs/${CID}`, requestOptions)
  if (!response.ok) throw new Response('Failed to get data', { status: 500 })
  return response.json()
}

/**
 * Fetch Universal Profile
 * @param {address} addr
 * @returns
 */

export const fetchProfile = async (addr) => {
  //console.log(addr)
  var contract = new web3.eth.Contract(LSP0ERC725Account.abi, addr)
  try {
    return contract.methods
      .getData('0x5ef83ad9559033e6e941db7d7c495acdce616347d28e90c7ce47cbfcfcad3bc5')
      .call()
      .then(async (data) => {
        data = data.substring(6, data.length)
        // console.log(data)
        //  data ="0x" + data.substring(6)
        //  console.log(data)
        // slice the bytes to get its pieces
        const hashFunction = '0x' + data.slice(0, 8)
        // console.log(hashFunction)
        const hash = '0x' + data.slice(76)
        const url = '0x' + data.slice(76)

        // console.log(hashFunction, ' | ', hash, ' | ', url)

        // check if it uses keccak256
        //  if (hashFunction === '0x6f357c6a') {
        // download the json file
        const json = await getIPFS(web3.utils.hexToUtf8(url).replace('ipfs://', '').replace('://', ''))
        return json
        // compare hashes
        if (web3.utils.keccak256(JSON.stringify(json)) === hash.replace(hashFunction, '')) {
          return json
        } else false
        // }
      })
  } catch (error) {
    console.log(error)
    return []
  }
}

/**
 * Connect wallet
 */
export const isWalletConnected = async () => {
  try {
    let accounts = await web3.eth.getAccounts()
    return accounts[0]
  } catch (error) {
    toast.error(error.message)
  }
}

export const isUPinstalled = () => PROVIDER && PROVIDER.isUniversalProfileExtension

export function AuthProvider({ children }) {
  const [wallet, setWallet] = useState(null)
  const [profile, setProfile] = useState(null)
  const [loading, setLoading] = useState(true)
  const navigate = useNavigate()
  const location = useLocation()

  function logout() {
    localStorage.removeItem('accessToken')
    navigate('/login')
    setUser(null)
  }

  const connectWallet = async () => {
    let loadingToast = toast.loading('Loading...')

    try {
      let accounts = await web3.eth.getAccounts()
      if (accounts.length === 0) await web3.eth.requestAccounts()
      accounts = await web3.eth.getAccounts()
      //console.log(accounts)
      setWallet(accounts[0])
      //fetchProfile(accounts[0]).then((res) => setProfile(res))
      toast.dismiss(loadingToast)
      toast.success(`Wallet Connected!`)
      navigate(`/`)
      return accounts[0]
    } catch (error) {
      toast.error(`The provider could not be found.`)
      toast.dismiss(loadingToast)
    }
  }

  useEffect(() => {
    console.log(`Contract`, import.meta.env.VITE_DFORM_CONTRACT_MAINNET)
    setLoading(true)
    isWalletConnected().then((addr) => {
      if (addr !== undefined) {
        console.log(addr)
        setWallet(addr)
        localStorage.setItem(`wallet_addr`, addr)
        setLoading(false)
        //fetchProfile(addr).then((res) => setProfile(res))
      }
      setLoading(false)
    })

    if (!localStorage.getItem(`defaultChain`)) localStorage.setItem(`defaultChain`, 'Ethereum')
  }, [])

  const value = {
    wallet,
    setWallet,
    profile,
    isUPinstalled,
    fetchProfile,
    setProfile,
    isWalletConnected,
    connectWallet,
    logout,
  }

  if (loading) return <small>Loading in Auth Context...</small>

  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>
}
