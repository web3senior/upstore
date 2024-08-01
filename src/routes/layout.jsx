import { useEffect, useState } from 'react'
import { Outlet, useLocation, Link, NavLink, useNavigate, useNavigation, ScrollRestoration } from 'react-router-dom'
import { Toaster } from 'react-hot-toast'
import { web3, _, useAuth } from './../contexts/AuthContext'
import MaterialIcon from './helper/MaterialIcon'
import Icon from './helper/MaterialIcon'
import Logo from './../../src/assets/logo.svg'
import LogoIcon from './../../src/assets/logo.svg'
import TelegramIcon from './../../src/assets/icon-telegram.svg'
import XIcon from './../../src/assets/icon-x.svg'
import CGIcon from './../../src/assets/icon-cg.svg'
import GitHubIcon from './../../src/assets/icon-github.svg'
import EthereumLogo from './../../src/assets/ethereum-logo.svg'
import ArbitrumLogo from './../../src/assets/arbitrum-logo.svg'
import LuksoLogo from './../../src/assets/lukso.svg'
import party from 'party-js'
import styles from './Layout.module.scss'

party.resolvableShapes['LogoIcon'] = `<img src="${LogoIcon}"/>`

const links = [
  { name: 'Submit your dApp', icon: null, target: '_blank', path: `https://docs.google.com/forms/d/e/1FAIpQLScUYz_4VjdcB9bMOilhN67cFdzF1U7XZ1o0XqQYkaxThwTijA/viewform` },
  // {
  //   name: 'Solutions',
  //   icon: null,
  //   target: '',
  //   path: `solution`,
  // },
  {
    name: 'Ecosystem',
    icon: null,
    target: '',
    path: `ecosystem`,
  },
  // {
  //   name: 'Documentation',
  //   icon: null,
  //   target: '',
  //   path: `fee`,
  // },
]

export default function Root() {
  const [network, setNetwork] = useState()
  const [isLoading, setIsLoading] = useState()
  const [defaultChain, setDefaultChain] = useState()
  const [chain, setChain] = useState([
    {
      name: `Ethereum`,
      logo: EthereumLogo,
    },
    {
      name: `Arbitrum`,
      logo: ArbitrumLogo,
    },
    {
      name: `LUKSO`,
      logo: LuksoLogo,
    },
  ])
  const auth = useAuth()

  /**
   * Selected chain
   * @returns
   */
  const SelectedChain = () => {
    const filteredChain = chain.filter((item, i) => item.name.toLowerCase() === defaultChain.toLowerCase())
    return <img alt={`${filteredChain[0].name}`} src={`${filteredChain[0].logo}`} title={`${filteredChain[0].name}`} />
  }

  useEffect(() => {}, [])

  return (
    <>
      <Toaster />
      <ScrollRestoration />

      <header className={`${styles.header}`}>
        <div className={`__container d-flex flex-row align-items-center justify-content-between`} data-width={`xxlarge`}>
          {/* Logo */}
          <Link to={`/`}>
            <figure className={`${styles['logo']}`}>
              <img alt={import.meta.env.VITE_TITLE} src={Logo} />
            </figure>
          </Link>

          {/* Nav */}
          <ul className={`${styles['nav']} d-flex flex-row align-items-center justify-content-start`}>
            {links.map((item, i) => {
              return (
                <li key={i}>
                  <NavLink to={item.path} target={item.target}>
                    {item.name}
                  </NavLink>
                </li>
              )
            })}
          </ul>

          {/* Connect */}
          <div className={`d-flex flex-row align-items-center justify-content-end`} style={{ columnGap: `.3rem` }}>
            <div
              className={`${styles['network']} d-flex align-items-center justify-content-end`}
              onClick={() => {
                document.querySelector(`.${styles['network-list']}`).classList.toggle(`d-none`)
              }}
            >
              {defaultChain && <SelectedChain />}
            </div>

            {!auth.wallet ? (
              <>
                <button
                  className={styles['connect-button']}
                  onClick={(e) => {
                    party.confetti(document.querySelector(`header`), {
                      count: party.variation.range(20, 40),
                      shapes: ['LogoIcon'],
                    })
                    auth.connectWallet()
                  }}
                >
                  Connect Wallet
                </button>
              </>
            ) : (
              <Link to={`/`} className={`${styles['profile']} d-f-c user-select-none`}>
                <div className={`${styles['profile__wallet']} d-f-c`}>
                  <svg height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                    <g>
                      <circle cx="12" cy="12" fill="#FF89341F" r="12"></circle>
                      <g transform="translate(4, 4) scale(0.3333333333333333)">
                        <path
                          clipRule="evenodd"
                          d="M24 0C26.2091 0 28 1.79086 28 4V10.7222C28 12.0586 29.6157 12.7278 30.5607 11.7829L35.314 7.02948C36.8761 5.46739 39.4088 5.46739 40.9709 7.02948C42.533 8.59158 42.533 11.1242 40.9709 12.6863L36.2179 17.4393C35.2729 18.3843 35.9422 20 37.2785 20H44C46.2091 20 48 21.7909 48 24C48 26.2091 46.2091 28 44 28H37.2783C35.9419 28 35.2727 29.6157 36.2176 30.5607L40.9705 35.3136C42.5326 36.8756 42.5326 39.4083 40.9705 40.9704C39.4084 42.5325 36.8758 42.5325 35.3137 40.9704L30.5607 36.2174C29.6157 35.2724 28 35.9417 28 37.2781V44C28 46.2091 26.2091 48 24 48C21.7909 48 20 46.2091 20 44V37.2785C20 35.9422 18.3843 35.2729 17.4393 36.2179L12.6866 40.9706C11.1245 42.5327 8.59186 42.5327 7.02977 40.9706C5.46767 39.4085 5.46767 36.8759 7.02977 35.3138L11.7829 30.5607C12.7278 29.6157 12.0586 28 10.7222 28H4C1.79086 28 0 26.2091 0 24C0 21.7909 1.79086 20 4 20L10.7219 20C12.0583 20 12.7275 18.3843 11.7826 17.4393L7.02939 12.6861C5.46729 11.124 5.4673 8.59137 7.02939 7.02928C8.59149 5.46718 11.1241 5.46718 12.6862 7.02928L17.4393 11.7824C18.3843 12.7273 20 12.0581 20 10.7217V4C20 1.79086 21.7909 0 24 0ZM24 33C28.9706 33 33 28.9706 33 24C33 19.0294 28.9706 15 24 15C19.0294 15 15 19.0294 15 24C15 28.9706 19.0294 33 24 33Z"
                          fill="#FF8934"
                          fillRule="evenodd"
                        ></path>
                      </g>
                    </g>
                  </svg>
                  <b> {auth.wallet && `${auth.wallet.slice(0, 4)}...${auth.wallet.slice(38)}`}</b>
                </div>
              </Link>
            )}

            <div className={`${styles['network-list']} ms-depth-4 d-none`}>
              <ul>
                {defaultChain &&
                  chain &&
                  chain.length > 0 &&
                  chain.map((item, i) => {
                    return (
                      <li
                        key={i}
                        onClick={() => {
                          localStorage.setItem(`defaultChain`, item.name)
                          window.location.reload()
                        }}
                      >
                        <figure className={`d-flex flex-row align-items-center justify-content-start`} style={{ columnGap: `.5rem` }}>
                          <img alt={`${item.name}`} src={item.logo} />
                          <figcaption>{item.name}</figcaption>
                          {item.name.toLowerCase() === defaultChain.toLowerCase() && <Icon name={`check`} style={{ marginLeft: `auto`, color: `var(--color-primary)` }} />}
                        </figure>
                      </li>
                    )
                  })}
              </ul>
            </div>
          </div>
        </div>
      </header>

      <main className={`${styles.main}`}>
        <Outlet />
      </main>

      <footer className={`${styles.footer}`}>
        <div className={`__container`} data-width={`large`}>
          <div className="grid grid--fit" style={{ '--data-width': `200px`, columnGap: `1rem` }}>
            <div className={`footer__card`}>
              <h3>{import.meta.env.VITE_NAME}</h3>
              <div className={`d-flex flex-row align-items-center justify-content-start ${styles['social']}`} style={{ columnGap: '.5rem' }}>
                <a href={`//x.com/ArattaLabs`} target={`_blank`}>
                  <img alt={`X`} src={XIcon} />
                </a>
                <a href={`//github.com/web3senior/bluepoint`} target={`_blank`}>
                  <img alt={`GitHub`} src={GitHubIcon} />
                </a>
                <a href={`//t.me/arattalabs`} target={`_blank`}>
                  <img alt={`Telegram`} src={TelegramIcon} />
                </a>
              </div>
              <p>
                @ {new Date().getFullYear()} {import.meta.env.VITE_NAME}. All rights reserved.
              </p>
            </div>

            <div className={`footer__card`}>
              <h3 className={`text-left`}>Use cases</h3>
              <ul className={`d-flex flex-column align-items-start justify-content-center`}>
                <li>
                  <Link to={``}>Telegram</Link>
                </li>
                <li>
                  <Link to={``}>Wallet Address</Link>
                </li>
              </ul>
            </div>

            <div className={`footer__card`}>
              <h3 className={`text-left`}>Explore</h3>
              <ul className={`d-flex flex-column align-items-start justify-content-center`}>
                <li>
                  <Link to={``}>Roadmap</Link>
                </li>
                <li>
                  <Link to={``}>Twitter</Link>
                </li>
                <li>
                  <Link to={``}>Telegram</Link>
                </li>
                <li>
                  <Link to={``}>Roadmap</Link>
                </li>
              </ul>
            </div>

            <div className={`footer__card`}>
              <h3 className={`text-left`}>Developers</h3>
              <ul className={`d-flex flex-column align-items-start justify-content-center`}>
                <li>
                  <Link to={``}>Contract</Link>
                </li>
                <li>
                  <Link to={``}>Admin</Link>
                </li>
                <li>
                  <Link to={``}>Repo</Link>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </footer>
    </>
  )
}
