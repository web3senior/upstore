import React, { Suspense, lazy } from "react"
import ReactDOM from "react-dom/client"
import { createBrowserRouter, RouterProvider, Navigate } from "react-router-dom"
import { AuthProvider } from "./contexts/AuthContext"
import "./index.scss"
import "./styles/global.scss"

import ErrorPage from "./error-page"
import Loading from "./routes/components/LoadingSpinner"
const Layout = lazy(() => import("./routes/layout.jsx"))
const UserLayout = lazy(() => import("./routes/user-layout.jsx"))
import SplashScreen from "./routes/splashScreen.jsx"
import Whitelist from "./routes/whitelist.jsx"
import Benefits from "./routes/benefits.jsx"
import Home from "./routes/home.jsx"
import App from "./routes/app.jsx"
import About from "./routes/about.jsx"
import Lyx from "./routes/lyx.jsx"

console.log(`%c🆙`, 'font-size:5rem')

const router = createBrowserRouter([
  {
    path: "/",
    element: (
      <Suspense fallback={<Loading />}>
        <AuthProvider>
          <Layout />
        </AuthProvider>
      </Suspense>
    ),
    errorElement: <ErrorPage />,
    children: [
      {
        index: true,
        element: <Home title={`Home`} />,
      },
      {
        path: ":appId",
        element: <App />,
      },
      {
        path: "/about",
        element: <About title={`About`} />,
      },
      {
        path: "whitelist",
        element: <Whitelist title={`Whitelist`} />,
      },
      {
        path: "Benefits",
        element: <Benefits title={`Benefits`} />,
      },
      {
        path: "lyx",
        element: <Lyx title={`LYX`} />,
      },
    ],
  },
  {
    path: "usr",
    element: (
      <Suspense fallback={<Loading />}>
        <AuthProvider>
          <UserLayout />
        </AuthProvider>
      </Suspense>
    ),
    errorElement: <ErrorPage />,
    children: [
      {
        index: true,
        element: <Navigate to="/" replace />,
      },
    ],
  },
  {
    path: ":username",
    element: <></>,
  },
  // {
  //   path: "donate",
  //   errorElement: <ErrorPage />,
  //   children: [
  //     {
  //       index: true,
  //       element: <Navigate to="/" replace />,
  //     },
  //     {
  //       path: ":wallet_addr",
  //       element: <Donate title={`Donate`} />,
  //     },
  //   ],
  // },
])

ReactDOM.createRoot(document.getElementById("root")).render(<RouterProvider router={router} />)
