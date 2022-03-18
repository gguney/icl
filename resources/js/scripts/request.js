import axios from 'axios'

const axiosInstance = axios.create({
    baseURL: process.env.MIX_API_URL + ':' + process.env.MIX_APP_PORT,
    timeout: 3000
})

export default axiosInstance
