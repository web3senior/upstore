import { useEffect, useRef, useState } from "react"
import { Link, useNavigate } from "react-router-dom"
import { Title } from "./helper/DocumentTitle"
import MaterialIcon from "./helper/MaterialIcon"
import { MenuIcon } from "./components/icons"
import Loading from "./components/LoadingSpinner"
import { CheckIcon, ChromeIcon, BraveIcon } from "./components/icons"
import toast, { Toaster } from "react-hot-toast"
import { useAuth, web3, _ } from "./../contexts/AuthContext"
import { addUp } from "./../util/api"
import UniversalFamShot from "./../../src/assets/universal-fam-shot.png"
import styles from "./Home.module.scss"
import up_mainnet_data from "./../../up_mainnet_export.json"
import algoliasearch from "algoliasearch"
import Logo from "./../../src/assets/logo.svg"

const ABI = [
  {
    inputs: [
      {
        internalType: "bytes32",
        name: "_whitelistId",
        type: "bytes32",
      },
    ],
    name: "addUser",
    outputs: [
      {
        internalType: "bool",
        name: "",
        type: "bool",
      },
    ],
    stateMutability: "nonpayable",
    type: "function",
  },
  {
    inputs: [
      {
        internalType: "string",
        name: "_metadata",
        type: "string",
      },
      {
        internalType: "uint256",
        name: "startTime",
        type: "uint256",
      },
      {
        internalType: "uint256",
        name: "endTime",
        type: "uint256",
      },
      {
        internalType: "address",
        name: "manager",
        type: "address",
      },
      {
        internalType: "address[]",
        name: "users",
        type: "address[]",
      },
    ],
    name: "addWhitelist",
    outputs: [
      {
        internalType: "bytes32",
        name: "",
        type: "bytes32",
      },
    ],
    stateMutability: "nonpayable",
    type: "function",
  },
  {
    inputs: [],
    stateMutability: "nonpayable",
    type: "constructor",
  },
  {
    inputs: [
      {
        internalType: "address",
        name: "owner",
        type: "address",
      },
    ],
    name: "OwnableInvalidOwner",
    type: "error",
  },
  {
    inputs: [
      {
        internalType: "address",
        name: "account",
        type: "address",
      },
    ],
    name: "OwnableUnauthorizedAccount",
    type: "error",
  },
  {
    inputs: [
      {
        internalType: "uint256",
        name: "time",
        type: "uint256",
      },
    ],
    name: "TooEarly",
    type: "error",
  },
  {
    inputs: [
      {
        internalType: "uint256",
        name: "time",
        type: "uint256",
      },
    ],
    name: "TooLate",
    type: "error",
  },
  {
    inputs: [],
    name: "Unauthorized",
    type: "error",
  },
  {
    anonymous: false,
    inputs: [
      {
        indexed: false,
        internalType: "string",
        name: "func",
        type: "string",
      },
      {
        indexed: false,
        internalType: "uint256",
        name: "gas",
        type: "uint256",
      },
    ],
    name: "Log",
    type: "event",
  },
  {
    anonymous: false,
    inputs: [
      {
        indexed: true,
        internalType: "address",
        name: "previousOwner",
        type: "address",
      },
      {
        indexed: true,
        internalType: "address",
        name: "newOwner",
        type: "address",
      },
    ],
    name: "OwnershipTransferred",
    type: "event",
  },
  {
    inputs: [
      {
        internalType: "address",
        name: "_addr",
        type: "address",
      },
      {
        internalType: "bytes32",
        name: "_whitelistId",
        type: "bytes32",
      },
    ],
    name: "removeUserByManager",
    outputs: [
      {
        internalType: "bool",
        name: "",
        type: "bool",
      },
    ],
    stateMutability: "nonpayable",
    type: "function",
  },
  {
    inputs: [],
    name: "renounceOwnership",
    outputs: [],
    stateMutability: "nonpayable",
    type: "function",
  },
  {
    inputs: [
      {
        internalType: "address[]",
        name: "_addrs",
        type: "address[]",
      },
      {
        internalType: "bytes32",
        name: "_whitelistId",
        type: "bytes32",
      },
    ],
    name: "setUserBatch",
    outputs: [],
    stateMutability: "nonpayable",
    type: "function",
  },
  {
    inputs: [
      {
        internalType: "address",
        name: "newOwner",
        type: "address",
      },
    ],
    name: "transferOwnership",
    outputs: [],
    stateMutability: "nonpayable",
    type: "function",
  },
  {
    inputs: [
      {
        internalType: "bytes32",
        name: "_whitelistId",
        type: "bytes32",
      },
      {
        internalType: "string",
        name: "_metadata",
        type: "string",
      },
      {
        internalType: "bool",
        name: "_pause",
        type: "bool",
      },
    ],
    name: "updateWhitelist",
    outputs: [
      {
        internalType: "bool",
        name: "",
        type: "bool",
      },
    ],
    stateMutability: "nonpayable",
    type: "function",
  },
  {
    anonymous: false,
    inputs: [
      {
        indexed: true,
        internalType: "address",
        name: "sender",
        type: "address",
      },
      {
        indexed: true,
        internalType: "bytes32",
        name: "id",
        type: "bytes32",
      },
      {
        indexed: false,
        internalType: "string",
        name: "metadata",
        type: "string",
      },
      {
        indexed: false,
        internalType: "uint256",
        name: "startTime",
        type: "uint256",
      },
      {
        indexed: false,
        internalType: "uint256",
        name: "endTime",
        type: "uint256",
      },
      {
        indexed: true,
        internalType: "address",
        name: "manager",
        type: "address",
      },
      {
        indexed: false,
        internalType: "bool",
        name: "pause",
        type: "bool",
      },
      {
        indexed: false,
        internalType: "address[]",
        name: "users",
        type: "address[]",
      },
    ],
    name: "WhitelistCreated",
    type: "event",
  },
  {
    inputs: [],
    name: "count",
    outputs: [
      {
        internalType: "uint256",
        name: "",
        type: "uint256",
      },
    ],
    stateMutability: "view",
    type: "function",
  },
  {
    inputs: [
      {
        internalType: "bytes32",
        name: "_whitelistId",
        type: "bytes32",
      },
    ],
    name: "getUserList",
    outputs: [
      {
        internalType: "address[]",
        name: "",
        type: "address[]",
      },
    ],
    stateMutability: "view",
    type: "function",
  },
  {
    inputs: [
      {
        internalType: "bytes32",
        name: "_whitelistId",
        type: "bytes32",
      },
    ],
    name: "getUserTotal",
    outputs: [
      {
        internalType: "uint256",
        name: "",
        type: "uint256",
      },
    ],
    stateMutability: "view",
    type: "function",
  },
  {
    inputs: [
      {
        internalType: "bytes32",
        name: "_whitelistId",
        type: "bytes32",
      },
    ],
    name: "getWhitelist",
    outputs: [
      {
        components: [
          {
            internalType: "bytes32",
            name: "id",
            type: "bytes32",
          },
          {
            internalType: "string",
            name: "metadata",
            type: "string",
          },
          {
            internalType: "uint256",
            name: "startTime",
            type: "uint256",
          },
          {
            internalType: "uint256",
            name: "endTime",
            type: "uint256",
          },
          {
            internalType: "address",
            name: "manager",
            type: "address",
          },
          {
            internalType: "bool",
            name: "pause",
            type: "bool",
          },
          {
            internalType: "address[]",
            name: "users",
            type: "address[]",
          },
        ],
        internalType: "struct WhitelistFactory.whitelistStruct",
        name: "",
        type: "tuple",
      },
    ],
    stateMutability: "view",
    type: "function",
  },
  {
    inputs: [],
    name: "owner",
    outputs: [
      {
        internalType: "address",
        name: "",
        type: "address",
      },
    ],
    stateMutability: "view",
    type: "function",
  },
  {
    inputs: [
      {
        internalType: "bytes32",
        name: "_whitelistId",
        type: "bytes32",
      },
      {
        internalType: "address",
        name: "_manager",
        type: "address",
      },
    ],
    name: "verifyManager",
    outputs: [
      {
        internalType: "bool",
        name: "",
        type: "bool",
      },
    ],
    stateMutability: "view",
    type: "function",
  },
  {
    inputs: [
      {
        internalType: "bytes32",
        name: "_whitelistId",
        type: "bytes32",
      },
      {
        internalType: "address",
        name: "_addr",
        type: "address",
      },
    ],
    name: "verifyUser",
    outputs: [
      {
        internalType: "bool",
        name: "",
        type: "bool",
      },
    ],
    stateMutability: "view",
    type: "function",
  },
  {
    inputs: [],
    name: "whitelistTotal",
    outputs: [
      {
        internalType: "uint256",
        name: "",
        type: "uint256",
      },
    ],
    stateMutability: "view",
    type: "function",
  },
]
const client = algoliasearch(import.meta.env.VITE_APPLICATION_ID, import.meta.env.VITE_API_KEY)
//prod_testnet_universal_profiles
const index = client.initIndex("prod_mainnet_universal_profiles")
let hits = []

function Page({ title }) {
  Title(title)
  const [up, setUp] = useState()
  const [whitelist, setWhitelist] = useState()
  const auth = useAuth()
  const navigate = useNavigate()
const timerRef=useRef()

  const runApp = async () => {
    // Get all records as an iterator
    index
      .browseObjects({
        batch: (batch) => {
          hits = hits.concat(batch)
        },
      })
      .then(() => {
        console.log(hits)
        setUp(hits)

        // hits.map((item, i) => {
        //  addUp({content:item})
        // })

        console.log(`Done`)
      })

    // index.search('"atenyun"').then(({ hits }) => {
    //   console.log(JSON.stringify(hits[0]))
    // })
  }

  const addMe = async () => {
    web3.eth.defaultAccount = `0x188eeC07287D876a23565c3c568cbE0bb1984b83`

    const whitelistFactoryContract = new web3.eth.Contract(
      [
        {
          inputs: [],
          stateMutability: "nonpayable",
          type: "constructor",
        },
        {
          inputs: [
            {
              internalType: "address",
              name: "owner",
              type: "address",
            },
          ],
          name: "OwnableInvalidOwner",
          type: "error",
        },
        {
          inputs: [
            {
              internalType: "address",
              name: "account",
              type: "address",
            },
          ],
          name: "OwnableUnauthorizedAccount",
          type: "error",
        },
        {
          inputs: [
            {
              internalType: "uint256",
              name: "time",
              type: "uint256",
            },
          ],
          name: "TooEarly",
          type: "error",
        },
        {
          inputs: [
            {
              internalType: "uint256",
              name: "time",
              type: "uint256",
            },
          ],
          name: "TooLate",
          type: "error",
        },
        {
          inputs: [],
          name: "Unauthorized",
          type: "error",
        },
        {
          anonymous: false,
          inputs: [
            {
              indexed: false,
              internalType: "string",
              name: "func",
              type: "string",
            },
            {
              indexed: false,
              internalType: "uint256",
              name: "gas",
              type: "uint256",
            },
          ],
          name: "Log",
          type: "event",
        },
        {
          anonymous: false,
          inputs: [
            {
              indexed: true,
              internalType: "address",
              name: "previousOwner",
              type: "address",
            },
            {
              indexed: true,
              internalType: "address",
              name: "newOwner",
              type: "address",
            },
          ],
          name: "OwnershipTransferred",
          type: "event",
        },
        {
          anonymous: false,
          inputs: [
            {
              indexed: true,
              internalType: "address",
              name: "sender",
              type: "address",
            },
            {
              indexed: true,
              internalType: "bytes32",
              name: "id",
              type: "bytes32",
            },
            {
              indexed: false,
              internalType: "string",
              name: "metadata",
              type: "string",
            },
            {
              indexed: false,
              internalType: "uint256",
              name: "startTime",
              type: "uint256",
            },
            {
              indexed: false,
              internalType: "uint256",
              name: "endTime",
              type: "uint256",
            },
            {
              indexed: true,
              internalType: "address",
              name: "manager",
              type: "address",
            },
            {
              indexed: false,
              internalType: "bool",
              name: "pause",
              type: "bool",
            },
          ],
          name: "WhitelistCreated",
          type: "event",
        },
        {
          inputs: [
            {
              internalType: "bytes32",
              name: "_whitelistId",
              type: "bytes32",
            },
          ],
          name: "addUser",
          outputs: [
            {
              internalType: "bool",
              name: "",
              type: "bool",
            },
          ],
          stateMutability: "nonpayable",
          type: "function",
        },
        {
          inputs: [
            {
              internalType: "string",
              name: "_metadata",
              type: "string",
            },
            {
              internalType: "uint256",
              name: "startTime",
              type: "uint256",
            },
            {
              internalType: "uint256",
              name: "endTime",
              type: "uint256",
            },
            {
              internalType: "address",
              name: "manager",
              type: "address",
            },
            {
              internalType: "address[]",
              name: "users",
              type: "address[]",
            },
          ],
          name: "addWhitelist",
          outputs: [
            {
              internalType: "bytes32",
              name: "",
              type: "bytes32",
            },
          ],
          stateMutability: "nonpayable",
          type: "function",
        },
        {
          inputs: [
            {
              internalType: "bytes32",
              name: "_whitelistId",
              type: "bytes32",
            },
          ],
          name: "getUserList",
          outputs: [
            {
              internalType: "address[]",
              name: "",
              type: "address[]",
            },
          ],
          stateMutability: "view",
          type: "function",
        },
        {
          inputs: [
            {
              internalType: "bytes32",
              name: "_whitelistId",
              type: "bytes32",
            },
          ],
          name: "getUserTotal",
          outputs: [
            {
              internalType: "uint256",
              name: "",
              type: "uint256",
            },
          ],
          stateMutability: "view",
          type: "function",
        },
        {
          inputs: [
            {
              internalType: "bytes32",
              name: "_whitelistId",
              type: "bytes32",
            },
          ],
          name: "getWhitelist",
          outputs: [
            {
              components: [
                {
                  internalType: "bytes32",
                  name: "id",
                  type: "bytes32",
                },
                {
                  internalType: "string",
                  name: "metadata",
                  type: "string",
                },
                {
                  internalType: "uint256",
                  name: "startTime",
                  type: "uint256",
                },
                {
                  internalType: "uint256",
                  name: "endTime",
                  type: "uint256",
                },
                {
                  internalType: "address",
                  name: "manager",
                  type: "address",
                },
                {
                  internalType: "bool",
                  name: "pause",
                  type: "bool",
                },
                {
                  internalType: "address[]",
                  name: "users",
                  type: "address[]",
                },
              ],
              internalType: "struct WhitelistFactory.whitelistStruct",
              name: "",
              type: "tuple",
            },
          ],
          stateMutability: "view",
          type: "function",
        },
        {
          inputs: [],
          name: "owner",
          outputs: [
            {
              internalType: "address",
              name: "",
              type: "address",
            },
          ],
          stateMutability: "view",
          type: "function",
        },
        {
          inputs: [
            {
              internalType: "address",
              name: "_addr",
              type: "address",
            },
            {
              internalType: "bytes32",
              name: "_whitelistId",
              type: "bytes32",
            },
          ],
          name: "removeUserByManager",
          outputs: [
            {
              internalType: "bool",
              name: "",
              type: "bool",
            },
          ],
          stateMutability: "nonpayable",
          type: "function",
        },
        {
          inputs: [],
          name: "renounceOwnership",
          outputs: [],
          stateMutability: "nonpayable",
          type: "function",
        },
        {
          inputs: [
            {
              internalType: "address[]",
              name: "_addrs",
              type: "address[]",
            },
            {
              internalType: "bytes32",
              name: "_whitelistId",
              type: "bytes32",
            },
          ],
          name: "setUserBatch",
          outputs: [],
          stateMutability: "nonpayable",
          type: "function",
        },
        {
          inputs: [
            {
              internalType: "address",
              name: "newOwner",
              type: "address",
            },
          ],
          name: "transferOwnership",
          outputs: [],
          stateMutability: "nonpayable",
          type: "function",
        },
        {
          inputs: [
            {
              internalType: "bytes32",
              name: "_whitelistId",
              type: "bytes32",
            },
            {
              internalType: "string",
              name: "_metadata",
              type: "string",
            },
            {
              internalType: "bool",
              name: "_pause",
              type: "bool",
            },
          ],
          name: "updateWhitelist",
          outputs: [
            {
              internalType: "bool",
              name: "",
              type: "bool",
            },
          ],
          stateMutability: "nonpayable",
          type: "function",
        },
        {
          inputs: [
            {
              internalType: "bytes32",
              name: "_whitelistId",
              type: "bytes32",
            },
            {
              internalType: "address",
              name: "_manager",
              type: "address",
            },
          ],
          name: "verifyManager",
          outputs: [
            {
              internalType: "bool",
              name: "",
              type: "bool",
            },
          ],
          stateMutability: "view",
          type: "function",
        },
        {
          inputs: [
            {
              internalType: "bytes32",
              name: "_whitelistId",
              type: "bytes32",
            },
            {
              internalType: "address",
              name: "_addr",
              type: "address",
            },
          ],
          name: "verifyUser",
          outputs: [
            {
              internalType: "bool",
              name: "",
              type: "bool",
            },
          ],
          stateMutability: "view",
          type: "function",
        },
        {
          inputs: [],
          name: "whitelistTotal",
          outputs: [
            {
              internalType: "uint256",
              name: "",
              type: "uint256",
            },
          ],
          stateMutability: "view",
          type: "function",
        },
      ],
      `0xc407722d150c8a65e890096869f8015D90a89EfD`,
      {
        from: "0x188eeC07287D876a23565c3c568cbE0bb1984b83", // default from address
        gasPrice: "20000000000",
      }
    )
    console.log(whitelistFactoryContract.defaultChain, Date.now())
    await whitelistFactoryContract.methods
      .addUser(`0x0000000000000000000000000000000000000000000000000000000000000001`)
      .send()
      .then((res) => {
        console.log(res)
      })
  }

  const fetchWhitelist = async () => {
    web3.eth.defaultAccount = `0x188eeC07287D876a23565c3c568cbE0bb1984b83`
    const whitelistFactoryContract = new web3.eth.Contract(ABI, `0xc407722d150c8a65e890096869f8015D90a89EfD`)
    return await whitelistFactoryContract.methods
      .getWhitelist(`0x0000000000000000000000000000000000000000000000000000000000000002`)
      .call()
      .then((res) => {
        setWhitelist(res)
        console.log(res)
        return res
      })
  }

  useEffect(() => {
    fetchWhitelist().then((data)=>{
      var countDownDate = new Date("Jan 5, 2030 15:37:25").getTime();
      var x = setInterval(function() {
        var now = new Date().getTime();
        var distance = countDownDate - now;
      
        // Time calculations for days, hours, minutes and seconds
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);
      
        // Display the result in the element with id="demo"
       timerRef.current.innerHTML = days + "d " + hours + "h "
        + minutes + "m " + seconds + "s ";
      
        // If the count down is finished, write some text
        if (distance < 0) {
          clearInterval(x);
         timerRef.current.innerHTML = "EXPIRED";
        }
      }, 1000);

    })
    // let randomUp = []
    // for (let i = 0; i < 260; i++) {
    //   let randomNum = Math.floor(Math.random() * (up_mainnet_data.length - 10000))
    //   if (up_mainnet_data[randomNum].profileImageUrl !== "") randomUp.push(up_mainnet_data[randomNum])
    // }
    // setUp(randomUp)
  }, [])
  return (
    <>
      <section className={styles.section}>


        {/* <div className={`${styles.grid} grid grid--fit`} style={{ "--data-width": "124px" }} hidden>
          {up &&
            up.map((item, i) => (
              <figure className={styles.pfp} key={i}>
                <img src={item.profileImageUrl} />
              </figure>
            ))}
        </div> */}

        <div className={`__container`} data-width={`medium`}>
          <figure className={styles['logo']}>
            <img src={Logo} />
          </figure>
          <div className={`card`}>
            <div className={`card__body d-flex flex-column align-items-center`} style={{ rowGap: "1.5rem" }}>
              <h4 className="text-center">The Universal Fam Collection</h4>
              <figure className={styles['hero']}>
                <img alt={`The Universal Fam NFT Collection`} src={UniversalFamShot} />
              </figure>
              <p>
                The Universal Fam Collection is a digital collectible that emphasizes community and appreciation. It provides privileged access to upcoming products from Aratta Labs and allows users
                to vote in the DAO industry for any community-driven products.
              </p>

              <span className={styles['discount']}>Special offer for early users: 20% off</span>
              <span className={styles['event']}>The <b>whitelist</b> is currently open for new members to join</span>
              <p ref={timerRef}></p>

              {whitelist && <>{new Date(web3.utils.toNumber(whitelist.endTime)).toString()}</>}

              <button className={`mt-20`}>Join</button>
            </div>
          </div>
        </div>

        {/* <div className={` d-flex align-items-center justify-content-center`}>
            <button className="btn mt-40" onClick={() => auth.connectWallet()}>
              Connect
            </button>
          </div> */}
      </section>
    </>
  )
}

export default Page
