const APIURL = document.body.dataset.apiUrl

export async function addEvent(username, event, name) {
  let requestOptions = {
    method: 'POST',
    redirect: 'follow',
  }
  const params = new URLSearchParams({ username: username, event: event, name: name }).toString()
  const response = await fetch(`${APIURL}event/add?${params}`, requestOptions)
  if (!response.ok) throw new Response('Failed to get data', { status: 500 })
  return response.json()
}
