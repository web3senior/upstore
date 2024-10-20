import { useState, useEffect, useRef } from 'react'
import { defer, useNavigate, useLoaderData } from 'react-router-dom'
import { Title } from './helper/DocumentTitle'
import Logo from './../../public/logo.svg'
import styles from './SplashScreen.module.scss'

const SplashScreen = ({ title }) => {
  Title(title)
  const navigate = useNavigate()
  const frameRef = useRef()

  useEffect(() => {
    // Close the page
    window.setTimeout(() => {
      frameRef.current.classList.add('animate__fadeOut')
    }, 1000)

    // Navigate to
    window.setTimeout(() => {
      navigate('/home')
    }, 1500)
  }, [])

  return (
    <section className={`${styles.section} animate__animated animate__fadeIn`} ref={frameRef} data-name={import.meta.env.VITE_NAME}>
      <figure>
        <img alt={import.meta.env.VITE_NAME} src={Logo} />
      </figure>
    </section>
  )
}

export default SplashScreen
