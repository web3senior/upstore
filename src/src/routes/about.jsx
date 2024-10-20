import { Title } from './helper/DocumentTitle'
import styles from './About.module.scss'

export default function About({ title }) {
  Title(title)

  return (
    <section className={styles.section}>
      <div className={`__container ms-motion-slideUpIn ${styles['container']}`} data-width={`small`}>
        <div className={`card ms-depth-4 text-justify`}>
          <div className="card__header">{title}</div>
          <div className="card__body">
            UPstore is built on blockchain technology, which distributes control among a network of computers. This means that there is no single authority that can decide what gets listed in a dapp
            store.
          </div>
        </div>
      </div>
    </section>
  )
}
