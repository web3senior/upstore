/**
 * u
 * @returns
 */
export async function getApp(filter='') {
  let requestOptions = {
    method: 'GET',
    redirect: 'follow',
  }
  const params = new URLSearchParams({ filter: filter }).toString()
  const response = await fetch(`${import.meta.env.VITE_API_URL}app/get?${params}`, requestOptions)
  if (!response.ok) throw new Response('Failed to get data', { status: 500 })
  return response.json()
}

/**
 * Dashboard
 * @returns
 */
export async function getDashboard(wallet_addr) {
  let requestOptions = {
    method: 'GET',
    redirect: 'follow',
  }
  const params = new URLSearchParams({ wallet_addr: wallet_addr }).toString()
  const response = await fetch(`${import.meta.env.VITE_API_URL}dashboard?${params}`, requestOptions)
  if (!response.ok) throw new Response('Failed to get data', { status: 500 })
  return response.json()
}

/**
 * Event
 * @returns
 */
export async function getEvent(wallet_addr) {
  let requestOptions = {
    method: 'GET',
    redirect: 'follow',
  }
  const params = new URLSearchParams({ wallet_addr: wallet_addr }).toString()
  const response = await fetch(`${import.meta.env.VITE_API_URL}event/get?${params}`, requestOptions)
  if (!response.ok) throw new Response('Failed to get data', { status: 500 })
  return response.json()
}

/**
 * Event chart
 * @returns
 */
export async function getEventChart(wallet_addr) {
  let requestOptions = {
    method: 'GET',
    redirect: 'follow',
  }
  const params = new URLSearchParams({ wallet_addr: wallet_addr }).toString()
  const response = await fetch(`${import.meta.env.VITE_API_URL}event/chart?${params}`, requestOptions)
  if (!response.ok) throw new Response('Failed to get data', { status: 500 })
  return response.json()
}
/**
 * View
 * @returns
 */
export async function getView(wallet_addr) {
  let requestOptions = {
    method: 'GET',
    redirect: 'follow',
  }
  const params = new URLSearchParams({ wallet_addr: wallet_addr }).toString()
  const response = await fetch(`${import.meta.env.VITE_API_URL}view/get?${params}`, requestOptions)
  if (!response.ok) throw new Response('Failed to get data', { status: 500 })
  return response.json()
}

/**
 * View
 * @returns
 */
export async function addEvent(username, event, name) {
  let requestOptions = {
    method: 'POST',
    redirect: 'follow',
  }
  const params = new URLSearchParams({ username: username, event: event, name: name }).toString()
  const response = await fetch(`${import.meta.env.VITE_API_URL}event/add?${params}`, requestOptions)
  if (!response.ok) throw new Response('Failed to get data', { status: 500 })
  return response.json()
}

/**
 * View chart
 * @returns
 */
export async function getViewChart(wallet_addr) {
  let requestOptions = {
    method: 'GET',
    redirect: 'follow',
  }
  const params = new URLSearchParams({ wallet_addr: wallet_addr }).toString()
  const response = await fetch(`${import.meta.env.VITE_API_URL}view/chart?${params}`, requestOptions)
  if (!response.ok) throw new Response('Failed to get data', { status: 500 })
  return response.json()
}

/**
 * View chart
 * @returns
 */
export async function getConfig(username, addr = '') {
  let requestOptions = {
    method: 'GET',
    redirect: 'follow',
  }
  const params = new URLSearchParams({ username: username, wallet_addr: addr }).toString()
  const response = await fetch(`${import.meta.env.VITE_API_URL}config/get?${params}`, requestOptions)
  if (!response.ok) throw new Response('Failed to get data', { status: 500 })
  return response.json()
}

/**
 * Link
 * @param {json} post
 * @returns
 */
export async function addUp(post) {
  var requestOptions = {
    method: 'POST',
    body: JSON.stringify(post),
    redirect: 'follow',
  }

  const response = await fetch(`${import.meta.env.VITE_API_URL}up/add`, requestOptions)
  if (!response.ok) throw new Response('Failed to get data', { status: 500 })
  return response.json()
}

/**
 * Check username
 * @param {*} post
 * @param {*} wallet_addr
 * @returns
 */
export async function checkUser(post, wallet_addr) {
  var requestOptions = {
    method: 'POST',
    body: JSON.stringify(post),
    redirect: 'follow',
  }
  const params = new URLSearchParams({ wallet_addr: wallet_addr }).toString()
  const response = await fetch(`${import.meta.env.VITE_API_URL}user/check?${params}`, requestOptions)
  if (!response.ok) throw new Response('Failed to get data', { status: 500 })
  return response.json()
}


/**
 * Update username
 * @param {*} post
 * @param {*} wallet_addr
 * @returns
 */
export async function updateUser(post, wallet_addr) {
  var requestOptions = {
    method: 'POST',
    body: JSON.stringify(post),
    redirect: 'follow',
  }
  const params = new URLSearchParams({ wallet_addr: wallet_addr }).toString()
  const response = await fetch(`${import.meta.env.VITE_API_URL}user/update?${params}`, requestOptions)
  if (!response.ok) throw new Response('Failed to get data', { status: 500 })
  return response.json()
}

/**
 * Update username
 * @param {*} post
 * @param {*} wallet_addr
 * @returns
 */
export async function updateTelegramId(post, wallet_addr) {
  var requestOptions = {
    method: 'POST',
    body: JSON.stringify(post),
    redirect: 'follow',
  }
  const params = new URLSearchParams({ wallet_addr: wallet_addr }).toString()
  const response = await fetch(`${import.meta.env.VITE_API_URL}user/telegram?${params}`, requestOptions)
  if (!response.ok) throw new Response('Failed to get data', { status: 500 })
  return response.json()
}
