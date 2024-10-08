export default ($axios /*, $sentry*/) => (resource) => ({
  list(page, filters) {
    page = page || 1
    filters = filters || {}
    const query = {
      page,
      ...filters
    }

    return $axios
      .get(`/${resource}`, { params: query })
      .then((resp) => {
        return {
          data: resp.data['hydra:member'] ? resp.data['hydra:member'] : resp,
          totalItems: resp.data['hydra:totalItems'],
          view: resp.data['hydra:view']
        }
      })
      .catch((e) => {
        // if 404
        if (e.response && e.response.status !== 404) {
          // $sentry.captureException(e)
        }
        throw e
      })
  },
  me(page, filters) {
    page = page || 1
    filters = filters || {}
    const query = {
      page,
      ...filters
    }

    return $axios
      .get(`/${resource}/me`, { params: query })
      .then((resp) => {
        return {
          data: resp.data['hydra:member'] ? resp.data['hydra:member'] : resp,
          totalItems: resp.data['hydra:totalItems'],
          view: resp.data['hydra:view']
        }
      })
      .catch((e) => {
        // $sentry.captureException(e)
        throw e
      })
  },
  child(id, subresource, filters = {}, page = 1) {
    const query = {
      page,
      ...filters
    }
    return $axios
      .get(`/${resource}/${id}/${subresource}`, { params: query })
      .then((resp) => {
        return {
          data: resp.data['hydra:member'],
          totalItems: resp.data['hydra:totalItems'],
          view: resp.data['hydra:view']
        }
      })
      .catch((e) => {
        if (e.response && e.response.status !== 404) {
          // $sentry.captureException(e)
        }
        throw e
      })
  },
  createChild(id, subresource, payload, config) {
    config = config || {}
    payload = payload || {}
    return $axios
      .post(`/${resource}/${id}/${subresource}`, payload, { ...config })
      .then((response) => response.data)
      .catch((e) => {
        // $sentry.captureException(e)
        throw e
      })
  },
  deleteChild(id, subresource) {
    return $axios
      .delete(`/${resource}/${id}/${subresource}`)
      .then((response) => response.data)
      .catch((e) => {
        // $sentry.captureException(e)
        throw e
      })
  },
  childItem(id, subresource, filters = {}, page = 1) {
    const query = {
      page,
      ...filters
    }
    return $axios
      .get(`/${resource}/${id}/${subresource}`, { params: query })
      .then((response) => response.data)
      .catch((e) => {
        if (e.response && e.response.status !== 404) {
          // $sentry.captureException(e)
        }
        throw e
      })
  },
  create(payload, config) {
    config = config || {}
    return $axios
      .post(`/${resource}`, payload, config)
      .then((response) => response.data)
      .catch((e) => {
        // $sentry.captureException(e)
        throw e
      })
  },
  get(id) {
    return $axios
      .get(`/${resource}/${id}`)
      .then((response) => response.data)
      .catch((e) => {
        if (e.response && e.response.status !== 404) {
          // $sentry.captureException(e)
        }
        throw e
      })
  },
  update(id, payload) {
    return $axios
      .put(`/${resource}/${id}`, payload)
      .then((response) => response.data)
      .catch((e) => {
        if (e.response && e.response.status !== 404) {
          // $sentry.captureException(e)
        }
        throw e
      })
  },
  delete(id) {
    return $axios
      .delete(`/${resource}/${id}`)
      .then((response) => response.data)
      .catch((e) => {
        // $sentry.captureException(e)
        throw e
      })
  },
  postWithId(id, payload) {
    return $axios
      .post(`/${resource}/${id}`, payload)
      .then((response) => response.data)
      .catch((e) => {
        // $sentry.captureException(e)
        throw e
      })
  },
  simplePost(path, payload) {
    return $axios
      .post(`/${resource}/${path}`, payload)
      .then((response) => response.data)
      .catch((e) => {
        // $sentry.captureException(e)
        throw e
      })
  },
  generateDocuments(id, payload, config) {
    config = config || {}
    payload = payload || {}
    return $axios
      .post(`/${resource}/${id}/generate_documents`, payload, { ...config })
      .then((response) => response.data)
      .catch((e) => {
        // $sentry.captureException(e)
        throw e
      })
  }
})
