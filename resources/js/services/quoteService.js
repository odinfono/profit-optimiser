import axios from 'axios'

export function calculateProfitability(data) {
  return axios.post('/api/quote', data)
}
