import axios from 'axios'

const http = axios.create({
    baseURL: '',
    timeout: 1000 * 60 * 5, // 5 mins timeout
    config: {},
})

http.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response.status === 401) {
            // Unauthorized or expired session
            window.location.href = '/login'
        } else if (error.response.status === 403) {
            // Access is forbidden due to user status
            alert('Access is forbidden')
        }

        return error
    }
)

export default http
