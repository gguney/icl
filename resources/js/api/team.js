import request from '../scripts/request'

export function index(){
    return request({
        url: '/api/teams',
        method: 'GET'
    })
}
