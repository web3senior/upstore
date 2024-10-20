import { useState, useEffect } from 'react'
import { defer, Await, Form, Link, useParams } from 'react-router-dom'
import { Title } from './helper/DocumentTitle'
import LoadingSpinner from './components/LoadingSpinner'
import { useAuth } from '../contexts/AuthContext'
import MaterialIcon from './helper/MaterialIcon'
import {  } from './../util/api'
import Heading from './helper/Heading'
import styles from './Gateway.module.scss'
import toast from 'react-hot-toast'

export default function Gateway({ title }) {
  Title(title)
  const [loaderpage, setLoaderpage] = useState()
  const [error, setError] = useState()
  const [isLoading, setIsLoading] = useState(false)
  const [page, setPage] = useState([])
  const [api, setApi] = useState()
  const [profile, setProfile] = useState([])
  const [balance, setBalance] = useState(0)
  const auth = useAuth()
  const params = useParams()

  const makeTransfer = async () => {
    API_PROMISE.then(async (result) => {
      const secondAddress = '5ECvW8Le7k7e9d2XkysEV68HVwzmbBCEAknMuhVtk6S7ApmF'
      // extension-dapp API: get address's injector
      const firstAddressInjector = await web3FromAddress(auth.wallet)
      const transferAmount = new BN(50)
      const unitAdjustment = new BN(10).pow(new BN(api.registry.chainDecimals[0]))
      const finalAmount = transferAmount.mul(unitAdjustment)

      await api.tx.balances.transferAllowDeath(secondAddress, finalAmount).signAndSend(auth.wallet, { signer: firstAddressInjector.signer }, (status) => console.log)
    })
  }

  const readBalance = async () => {
    API_PROMISE.then(async (result) => {
      console.log(result)
      setApi(result)
      let {
        data: { free: previousFree },
        nonce: previousNonce,
      } = await result.query.system.account(auth.wallet)

      setBalance(previousFree.toString())

      console.log(`${auth.wallet} has a balance of ${previousFree}, nonce ${previousNonce}`)
    })
  }

  useEffect(() => {
    readBalance()

    // getPage(auth.wallet).then((result) => {
    //   console.log(result, JSON.parse(result[0].links))
    //   setPage(result)
    // })
  }, [])

  return (
    <>
      {page && page.length > 0 && (
        <>
          <header className={`d-flex align-items-center justify-content-center`}>
            <ul>
              <li>
                <h3>{page[0].name}</h3>
              </li>
              <li>{page[0].url}</li>
            </ul>
          </header>

          <main>
            <section className={`${styles.section} animate fade`}>
              <div className={`${styles.container}`}>
                <ul className={`${styles.header} d-flex flex-row align-items-center justify-content-between`}>
                  <li className="d-flex flex-column">
                    <b>Network</b>
                    <span>Order ID: #donation</span>
                  </li>
                  <li className="d-flex flex-column">
                    <select name="" id="">
                      <option value="">Aleph Zreo</option>
                    </select>
                  </li>
                </ul>

                <div className={`${styles.subContainer}  d-flex flex-column align-items-center justify-content-between`}>
                  <ul className={`${styles.address} d-flex flex-row align-items-center justify-content-between`}>
                    <li className="d-flex flex-column">
                      <span>To:</span>
                    </li>
                    <li className="d-flex flex-column" title={page[0].wallet_addr}>
                      <span>{`${page[0].wallet_addr.slice(0, 4)}...${page[0].wallet_addr.slice(page[0].wallet_addr.length - 4, page[0].wallet_addr.length)}`}</span>
                    </li>
                    
                    <li>
                      <MaterialIcon name={`content_copy`} />
                    </li>
                  </ul>

                  

                  <h3 className={`${styles.amount} text-center`}>{page[0].amount} $AZERO</h3>

                  <div>
                      <input type="number" name="" id="" placeholder='Amount $AZERO' defaultValue={50}/>
                      <small>Default value is 50</small>
                    </div>
                  
                  <div className="text-center">
                    Your current balance: <small className="badge badge-pill badge-success">{balance}</small> $AZERO
                  </div>

                  <button className="btn" onClick={() => makeTransfer()}>
                    Make payment
                  </button>

                  {/* <ul className={`${styles.need} d-flex flex-row align-items-center justify-content-between`}>
                    <li className="d-flex flex-row">
                      <MaterialIcon name={`attach_money`} /> <span>Need AZERO for purchasing?</span>
                    </li>
                    <li className="d-flex flex-column">
                      <Link to={`/`} className="text-primary">
                        Borrow it
                      </Link>
                    </li>
                  </ul> */}

                  <ul className={`${styles.details} d-flex flex-column align-items-center justify-content-between`}>
                    <li className="d-flex flex-row align-items-center justify-content-between">
                      <span>1 $AZERO is</span>
                      <span>1$</span>
                    </li>
                    <li className="d-flex flex-row align-items-center justify-content-between">
                      <span>1$ is</span>
                      <span>1 $AZERO</span>
                    </li>
                    <li className="d-flex flex-row align-items-center justify-content-between">
                      <span>Compared to </span>
                      <span>Within 0%</span>
                    </li>
                  </ul>
                </div>
              </div>

              <a href={`./`} className={styles.cancel}>
                Cancel
              </a>
            </section>
          </main>
        </>
      )}
    </>
  )
}
