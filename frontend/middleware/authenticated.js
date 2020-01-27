const jwt = require('jsonwebtoken')

export default function ({ store, redirect }) {
    if (
        !store.state.user.token
        || !store.state.user.id
        //|| !jwt.verify(store.state.user.token, process.env.JWT_SECRET)
    ) {
      return redirect('/login')
    }
}