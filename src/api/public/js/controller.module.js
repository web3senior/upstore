// Host URL
const url = document.body.dataset.url // production: https://data.mayaexchange.ca
const port = '3000'
const host = url //`${url}:${port}/` // Always with slash(/) at the end

/**
 * Select All
 * return all rows
 * @param {String} endpoint
 * @returns {Array}
 */
export const SelectAll = async (endpoint) => {
    const requestOptions = {
        method: 'GET',
        redirect: 'follow'
    }
    return await fetch(`${host}${endpoint}`, requestOptions)
        .then(response => response.json())
        .then(result => result)
        .catch(error => error)
}

/**
 * Select
 * return specific row
 * @param {String} endpoint
 * @param {Integer} id
 * @returns {Array}
 */
export const Select = async (endpoint, id) => {
    const requestOptions = {
        method: 'GET',
        redirect: 'follow',
    }

    return await fetch(`${host}${endpoint}/${id}`, requestOptions)
        .then(response => response.json())
        .then(result => result)
        .catch(error => error)
}

/**
 * Update
 * update rocord
 * @param {String} endpoint
 * @param {Integer} id
 * @param {Array} arrData // e.g. [["field1", "val"], ["field2", "val"]]
 * @returns {Array}
 */
export const Update = async (endpoint, id, arrData) => {
    let myHeaders = new Headers()
    myHeaders.append("Content-Type", "application/x-www-form-urlencoded")

    let urlencoded = new URLSearchParams()
    arrData.forEach(element => {
        urlencoded.append(element[0], element[1])
    })

    let requestOptions = {
        method: 'POST',
        headers: myHeaders,
        body: urlencoded,
        redirect: 'follow'
    }

    return await fetch(`${host}${endpoint}/${id}`, requestOptions)
        .then(response => response.json())
        .then(result => result)
        .catch(error => error)
}

export const Insert = async (endpoint, arrData) => {
    let myHeaders = new Headers()
    myHeaders.append("Content-Type", "application/x-www-form-urlencoded")

    let urlencoded = new URLSearchParams()
    arrData.forEach(element => {
        urlencoded.append(element[0], element[1])
    })

    let requestOptions = {
        method: 'POST',
        headers: myHeaders,
        body: urlencoded,
        redirect: 'follow'
    }

    return await fetch(`${host}${endpoint}`, requestOptions)
        .then(response => response.json())
        .then(result => result)
        .catch(error => error)
}

export const Del = async (endpoint, id) => {
    const requestOptions = {
        method: 'GET',
        redirect: 'follow',
    }

    return await fetch(`${host}${endpoint}/${id}`, requestOptions)
        .then(response => response.json())
        .then(result => result)
        .catch(error => error)
}