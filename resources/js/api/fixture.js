import request from '../scripts/request'

export function index(){
    return request({
        url: '/api/fixtures',
        method: 'GET'
    })
}

export function generate(){
  return request({
    url: '/api/fixtures/generate',
    method: 'POST'
  })
}

export function update(fixtureId, data){
  return request({
    url: '/api/fixtures/' + fixtureId,
    method: 'PATCH',
    data
  })
}

export function playNextWeek(){
    return request({
        url: '/api/fixtures/play-next-week',
        method: 'POST'
    })
}

export function playAll(){
    return request({
        url: '/api/fixtures/play-all',
        method: 'POST'
    })
}

export function resetAllFixtures(){
    return request({
        url: '/api/fixtures/reset-all',
        method: 'POST'
    })
}
