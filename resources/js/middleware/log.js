export default function log({to, next}) {
    console.log('middleware log')
    // console.log(next)

    next()
}
