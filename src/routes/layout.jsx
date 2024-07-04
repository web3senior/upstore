import { useEffect, useState } from 'react'
import { Outlet, useLocation, Link, NavLink, useNavigate, useNavigation } from 'react-router-dom'
import { Toaster } from 'react-hot-toast'
import { useAuth } from './../contexts/AuthContext'
import MaterialIcon from './helper/MaterialIcon'
import Shimmer from './helper/Shimmer'
import styles from './Layout.module.scss'
import Logo from './../../src/assets/logo.svg'
import Aratta from './../../src/assets/aratta.svg'
import Lukso from './../../src/assets/lukso.svg'
import Arbitrum from './../../src/assets/arbitrum-logo.svg'
import UniversalProfile from './../../src/assets/universal-profile.svg'
import party from 'party-js'

party.resolvableShapes['UniversalProfile'] = `<img src="${UniversalProfile}"/>`
party.resolvableShapes['Lukso'] = `<img src="${Lukso}"/>`

let links = [
  {
    name: 'Submit your dApp',
    icon: null,
    target: '_blank',
    path: `https://docs.google.com/forms/d/e/1FAIpQLScUYz_4VjdcB9bMOilhN67cFdzF1U7XZ1o0XqQYkaxThwTijA/viewform`,
  },
  {
    name: 'Contract',
    icon: null,
    target: '_blank',
    path: `https://explorer.execution.mainnet.lukso.network/address/${import.meta.env.VITE_UPSTORE_CONTRACT_MAINNET}`,
  },
]

export default function Root() {
  const [network, setNetwork] = useState()
  const [isLoading, setIsLoading] = useState()
  const noHeader = ['/sss']
  const auth = useAuth()
  const navigate = useNavigate()
  const navigation = useNavigation()
  const location = useLocation()

  const SelectedChain = () => {
    let defaultChain = localStorage.getItem(`defaultChain`)
    let chainInfo
    switch (defaultChain) {
      case `arbitrum`:
        chainInfo = (
          <>
            <img alt={`Arbitrum`} src={Arbitrum} />
            <span>Arbitrum</span>
          </>
        )
        break
      case `lukso`:
        chainInfo = (
          <>
            <img alt={`Lukso`} src={Lukso} />
            <span>Lukso</span>
          </>
        )
        break

      default:
        break
    }

    return chainInfo
  }

  return (
    <>
      <Toaster />
      <div className={styles.layout}>
        <header className={`${styles.header}`}>
          <div className={`${styles.container} __containerss`} data-width={`xlarge`}>
            <div className={`${styles['left']} d-flex flex-row align-items-center justify-content-start`}>
              <Link to={'/'} className={`${styles['logo']}`}>
                <b>{import.meta.env.VITE_NAME}</b>
              </Link>

              <ul className={`${styles['nav']} d-flex flex-row align-items-center justify-content-center`}>
                {location.pathname === '/' &&
                  links.map((item, i) => (
                    <li key={i}>
                      <Link to={item.path} target={item.target} className={`${({ isActive, isPending }) => (isPending ? 'pending' : isActive ? styles['active'] : '')}`}>
                        {item.name}
                      </Link>
                    </li>
                  ))}
              </ul>
            </div>

            <div className={`${styles['actions']} d-flex align-items-center justify-content-end`}>
              <div className={`${styles['network']} d-flex align-items-center justify-content-end`}>
                <SelectedChain />

                <div className={`${styles['network__sub']}`}>
                  <ul className={`w-100`}>
                    <li
                      className={`d-flex flex-row align-items-center justify-content-center w-100`}
                      onClick={() => {
                        localStorage.setItem(`defaultChain`, 'arbitrum')
                        window.location.reload()
                      }}
                    >
                      <img alt={`Arbitrum`} src={Arbitrum} />
                      <span>Arbitrum</span>
                    </li>

                    <li
                      className={`d-flex flex-row align-items-center justify-content-center w-100`}
                      onClick={() => {
                        localStorage.setItem(`defaultChain`, 'lukso')
                        window.location.reload()
                      }}
                    >
                      <img alt={`Lukso`} src={Lukso} />
                      <span>Lukso</span>
                    </li>
                  </ul>
                </div>
              </div>

              <div className={`d-flex flex-row align-items-center justify-content-end`}>
                {!auth.wallet ? (
                  <>
                    <button
                      className={styles['connect-button']}
                      onClick={(e) => {
                        party.confetti(document.querySelector(`.connect-btn-party-holder`), {
                          count: party.variation.range(20, 40),
                          shapes: ['UniversalProfile'],
                        })
                        auth.connectWallet()
                      }}
                    />
                  </>
                ) : (
                  <div className={`${styles['profile']} d-flex flex-row align-items-center justify-content-end`}>
                    <figure>
                      <img
                        alt={``}
                        src={`https://ipfs.io/ipfs/${
                          auth.profile && auth.profile.LSP3Profile.profileImage.length > 0 && auth.profile.LSP3Profile.profileImage[0].url.replace('ipfs://', '').replace('://', '')
                        }`}
                      />
                    </figure>

                    <ul className={`${styles['profile']}`}>
                      <li className={`d-flex flex-row align-items-center justify-content-stretch`}>
                        <figure>
                          <img
                            alt={``}
                            src={`https://ipfs.io/ipfs/${
                              auth.profile && auth.profile.LSP3Profile.profileImage.length > 0 && auth.profile.LSP3Profile.profileImage[0].url.replace('ipfs://', '').replace('://', '')
                            }`}
                          />
                        </figure>
                        <div className={`d-flex flex-column align-items-center justify-content-center`}>
                          <b>Hi, {auth.profile && auth.profile.LSP3Profile.name}</b>
                          <span>{auth.wallet && `${auth.wallet.slice(0, 4)}...${auth.wallet.slice(38)}`}</span>
                        </div>
                      </li>
                      <li>My dApps</li>
                      <li>Settings</li>
                      <li>Disconnect</li>
                    </ul>
                  </div>
                )}
              </div>

              <div className={`connect-btn-party-holder`} />
            </div>
          </div>

          <ul className={`${styles['mini-nav']} d-flex flex-row align-items-center justify-content-center`}>
            {links.map((item, i) => (
              <li key={i}>
                <NavLink to={item.path} target={item.target} className={`${({ isActive, isPending }) => (isPending ? 'pending' : isActive ? 'active' : '')}`}>
                  {item.name}
                </NavLink>
              </li>
            ))}
          </ul>
        </header>

        <main>
          <Outlet />
        </main>

        <footer>
          <a href={`//aratta.dev`} target={`_blank`}>
            <figure>
              <img alt={import.meta.env.AUTHOR} src={Aratta} />
            </figure>
          </a>
        </footer>
      </div>
    </>
  )
}
