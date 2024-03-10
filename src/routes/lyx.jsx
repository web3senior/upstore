import { Title } from './helper/DocumentTitle'
import { SingleTicker } from 'react-ts-tradingview-widgets'
import styles from './Lyx.module.scss'

function Lyx({ title }) {
  Title(title)

  return (
    <>
      <section className={styles.section}>
        <div className={`__container`} data-width={`medium`}>
          <div className={`card`}>
            <div className={`card__body`}>
              <SingleTicker width={`100%`} symbol={`CRYPTO:LYXUSD`} locale={`en`} isTransparent={false} colorTheme={`light`} />
            </div>
          </div>
        </div>
      </section>
    </>
  )
}

export default Lyx
