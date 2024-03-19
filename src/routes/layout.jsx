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
    name: 'NFT',
    icon: null,
    path: `/`,
  },
  {
    name: 'DAO',
    icon: null,
    path: `/dao`,
  },
  {
    name: 'DeFi',
    icon: null,
    path: `/defi`,
  },
  {
    name: 'Gaming',
    icon: null,
    path: `/gaming`,
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

  const handleNavLink = (route) => {
    if (route) navigate(route)
    handleOpenNav()
  }

  const handleOpenNav = () => {
    document.querySelector('#modal').classList.toggle('open')
    document.querySelector('#modal').classList.toggle('blur')
    document.querySelector('.cover').classList.toggle('showCover')
  }
  
  useEffect(() => {}, [])

  return (
    <>
      <Toaster />
      <div className={styles.layout}>
        <header className={`${styles.header}`}>
          <div className={`${styles.container} __containerss`} data-width={`xlarge`}>
            <div className={`${styles['left']} d-flex flex-row align-items-center justify-content-start`}>
              <Link to={'/'} className={styles['logo']}>
                <b>UP Store</b>
              </Link>

              <ul className={`${styles['nav']} d-flex flex-row align-items-center justify-content-center`}>
                {location.pathname === '/' &&
                  links.map((item, i) => (
                    <li key={i}>
                      <NavLink to={item.path} target={item.target} className={({ isActive, isPending }) => (isPending ? 'pending' : isActive ? styles['active'] : '')}>
                        {item.name}
                      </NavLink>
                    </li>
                  ))}
              </ul>
            </div>

            <div className={`${styles['actions']} d-flex align-items-center justify-content-end`}>
              <div className={`d-flex flex-column align-items-center justify-content-end`}>
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
                      <img alt={``} src={`https://ipfs.io/ipfs/${auth.profile && auth.profile.LSP3Profile.profileImage[0].url.replace('ipfs://', '').replace('://', '')}`} />
                    </figure>

                    <ul>
                      <li className={`d-flex flex-row align-items-center justify-content-stretch`}>
                        <figure>
                          <img alt={``} src={`https://ipfs.io/ipfs/${auth.profile && auth.profile.LSP3Profile.profileImage[0].url.replace('ipfs://', '').replace('://', '')}`} />
                        </figure>
                        <div className={`d-flex flex-column align-items-center justify-content-center`}>
                          <b>Hi, {auth.profile && auth.profile.LSP3Profile.name}</b>
                          <span>{auth.wallet && `${auth.wallet.slice(0, 4)}...${auth.wallet.slice(38)}`}</span>
                        </div>
                      </li>
                      <li>My Dapps</li>
                      <li>Settings</li>
                      <li>Disconnect</li>
                    </ul>
                  </div>
                )}
                <div className={`connect-btn-party-holder`} />
              </div>
            </div>
          </div>

          <ul className={`${styles['mini-nav']} d-flex flex-row align-items-center justify-content-center`}>
            {links.map((item, i) => (
              <li key={i}>
                <NavLink to={item.path} target={item.target} className={({ isActive, isPending }) => (isPending ? 'pending' : isActive ? 'active' : '')}>
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
          <ul className={`d-flex flex-row align-items-center justify-content-center`}>
            <li>
              <NavLink to={`new`} className={({ isActive, isPending }) => (isPending ? styles['pending'] : isActive ? styles['active'] : '')}>
                Submit your dapp
              </NavLink>
            </li>
            <li>
              <NavLink to={`about`} className={({ isActive, isPending }) => (isPending ? styles['pending'] : isActive ? styles['active'] : '')}>
                About
              </NavLink>
            </li>
          </ul>
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
