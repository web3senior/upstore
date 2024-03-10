import { useEffect, useState } from 'react'
import { Outlet, useLocation, Link, NavLink, useNavigate, useNavigation } from 'react-router-dom'
import { Toaster } from 'react-hot-toast'
import { useAuth } from '../contexts/AuthContext'
import MaterialIcon from './helper/MaterialIcon'
import { MenuIcon, Search } from './components/icons'
import styles from './UserLayout.module.scss'
import Logo from './../../src/assets/logo.svg'

export default function UserLayout() {
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
  useEffect(() => {
  }, [])

  return (
    <>
      <Toaster />
      <div className={styles.layout}>
        <header className={`${styles.header}`}>
          <div className={`${styles[`header__container`]} d-flex align-items-center justify-content-between`}>
            <Link to={'/'}>
              <div className={`${styles.logo} d-flex align-items-center`}>
                <figure>
                  <img src={Logo} alt={`logo`} />
                </figure>
                <b>{import.meta.env.VITE_NAME}</b>
              </div>
            </Link>

            <div className={`${styles.discover} d-flex flex-row align-items-center justify-content-center`}>
              <Search />
              <input type="text" placeholder="Discover" />
            </div>

            <div className={`d-flex align-items-center`} style={{ columnGap: '1rem' }}>
              <button className={`${styles.network} d-flex flex-row align-items-center justify-content-center`}>
                <svg width="18" height="17" viewBox="0 0 18 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <rect x="0.247681" width="17" height="17" rx="8.5" fill="#FE005B" fillOpacity="0.12" />
                  <path
                    d="M12.9628 5.01824L9.68645 3.14731C9.10628 2.81475 8.38905 2.81475 7.80889 3.14731L4.53253 5.01824C3.95237 5.3508 3.59375 5.96392 3.59375 6.62719V10.3709C3.59375 11.0342 3.95237 11.6473 4.53253 11.9799L7.80889 13.8526C8.38905 14.1852 9.10628 14.1852 9.68645 13.8526L12.9628 11.9799C13.543 11.6473 13.9016 11.0342 13.9016 10.3709V6.62719C13.9016 5.96392 13.5448 5.3508 12.9628 5.01824ZM11.3481 8.87156L10.3736 10.5418C10.2403 10.7722 9.99249 10.9134 9.724 10.9134H7.77321C7.50472 10.9134 7.25688 10.7722 7.12357 10.5418L6.14724 8.87156C6.01393 8.64117 6.01393 8.35877 6.14724 8.12839L7.1217 6.45812C7.255 6.22774 7.50284 6.08654 7.77133 6.08654H9.72025C9.98874 6.08654 10.2366 6.22774 10.3699 6.45812L11.3443 8.12839C11.4814 8.35877 11.4814 8.64117 11.3481 8.87156Z"
                    fill="#FE005B"
                  />
                </svg>

                <span>Lukso</span>

                <svg width="10" height="7" viewBox="0 0 10 7" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M4.96193 6.07148L0.247559 1.41601L0.741206 0.928528L4.96193 5.10158L9.18265 0.933592L9.6763 1.42107L4.96193 6.07148Z" fill="#7D7D7D" />
                </svg>
              </button>

              <button className={styles.wallet}>{auth.wallet && `${auth.wallet.slice(0, 4)}...${auth.wallet.slice(38)}`}</button>

              <button className={styles.navButton} onClick={() => handleNavLink()}>
                <MenuIcon />
              </button>
            </div>
          </div>
        </header>

        <main>
          <Outlet />
        </main>

        <footer>
          <ul className={`d-flex align-items-center justify-content-center`}>
            <li>
              <NavLink
                to={auth.wallet}
                className={({ isActive, isPending, isTransitioning }) =>
                  [isPending ? 'pending' : '', isActive ? styles.active : '', isTransitioning ? 'transitioning' : ''].join(' ')
                }
              >
                <MaterialIcon name="space_dashboard" />
                <i>Dashboard</i>
              </NavLink>
            </li>
            <li>
              <NavLink
                to={`links`}
                className={({ isActive, isPending, isTransitioning }) =>
                  [isPending ? 'pending' : '', isActive ? styles.active : '', isTransitioning ? 'transitioning' : ''].join(' ')
                }
              >
                <MaterialIcon name="dataset_linked" />
                <i>Links</i>
              </NavLink>
            </li>
            <li>
              <NavLink
                to={`setting`}
                className={({ isActive, isPending, isTransitioning }) =>
                  [isPending ? 'pending' : '', isActive ? styles.active : '', isTransitioning ? 'transitioning' : ''].join(' ')
                }
              >
                <MaterialIcon name="settings" />
                <i>Setting</i>
              </NavLink>
            </li>
          </ul>
        </footer>
      </div>

      <div className="cover" onClick={() => handleOpenNav()} />
      <nav className={`${styles.nav} animate`} id="modal">
        <figure>
          <img src={Logo} alt={`logo`} />
        </figure>
        
        <ul>
          <li className="">
            <button onClick={() => handleNavLink(`/`)}>
              <MaterialIcon name="home" />
              <span>Home</span>
            </button>
          </li>
          <li className="">
            <button onClick={() => handleNavLink(`/about`)}>
              <MaterialIcon name="info" />
              <span>About us</span>
            </button>
          </li>
        </ul>

        <small>{`Version ${import.meta.env.VITE_VERSION}`}</small>
      </nav>
    </>
  )
}
