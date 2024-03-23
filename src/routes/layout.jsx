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
    path: `https://explorer.execution.mainnet.lukso.network/address/0x83CE417862adB6Fa48685Ca1a6497e4eC871e692`,
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

  return (
    <>
      <Toaster />
      <div className={styles.layout}>
        <header className={`${styles.header}`}>
          <div className={`${styles.container} __containerss`} data-width={`xlarge`}>
            <div className={`${styles['left']} d-flex flex-row align-items-center justify-content-start`}>
              <Link to={'/'} className={`${styles['logo']}`}>
                <b>UP Store</b>
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
                <img alt={`Lukso`} src={Lukso} />
                <span>Lukso</span>
                <svg width="11" height="6" viewBox="0 0 11 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M5.69247 6.00759L0.384766 0.699887L1.09247 -0.0078125L5.69247 4.59219L10.2925 -0.0078125L11.0002 0.699887L5.69247 6.00759Z" fill="black" />
                </svg>
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
