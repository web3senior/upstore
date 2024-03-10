import { useEffect } from 'react'
import { Title } from './helper/DocumentTitle'
import Solidgrant from './../assets/solid-grant.svg'
import styles from './Benefits.module.scss'

function Benefits({ title }) {
  Title(title)

  useEffect(() => {}, [])
  return (
    <>
      <section className={styles.section}>
        <div className={`__container`} data-width={`medium`}>
          <div className={`card`}>
            <div className={`card__body d-flex flex-column`}>
              <figure className='mb-20'>
                <img alt={`Solid Grant Logo`} src={Solidgrant} />
                <figcaption><b>Built for community, by community!</b></figcaption>
              </figure>

              <p>We will implement a token-based voting mechanism in our DAO projects. This token will provide a way to vote on projects that you would like to prioritize and grow.</p>

              <p className={`mt-20`}>Community vote DAOs offer several benefits, including:</p>
              <ul className={`mt-20`}>
                <li>
                  <p><b>Transparency</b></p>
                  <p> The voting process is typically recorded on the blockchain, making it transparent and auditable.</p>
                </li>
                <li>
                  <p><b>Inclusiveness</b></p>
                  <p>All community members have a voice in decision-making</p>
                </li>
                <li>
                  <p><b>Decentralization</b></p>
                  <p>Decisions are not made by a single authority figure, but by the collective will of the community.</p>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </section>
    </>
  )
}

export default Benefits
