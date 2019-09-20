import styled from '@emotion/styled';
const primary = '#8797a0';
const secondary = '#a0cd4f';


export const Title = styled.h1`
    font-size: 2rem;
    margin: .67em 0;
`;

export const UnderlinedPrimary = styled.span`
    position: relative;
    &::after {
        position: absolute;
        height: 3px;
        top: 100%;
        left: 0;
        right: 0;    
        background: ${primary};
        content: ' ';
        display: block;
    }
`

export const UnderlinedSecondary = styled.span`
    position: relative;
    &::after {
        position: absolute;
        height: 3px;
        top: 100%;
        left: 0;
        right: 0;    
        background: ${secondary};
        content: ' ';
        display: block;
    }
`;


export const Card = styled.section`
    background: #ffffff;
    box-shadow: 0 3px 6px rgba(51, 51, 51, 0.4);
    display: block;
    font-size: 16px;
    
    & .title {
        color: ${primary};
        border-bottom: 1px solid rgba(51, 51, 51, 0.16);
        display: block;
        padding: 8px 12px;
        font-weight: bold;
    }
    
    & .content {
        padding: 8px 12px;
        display: block;
    }
`;

export const Columns = styled.section`
    display: grid;
    grid-template-columns: 1fr 1fr;
    grid-gap: 1.5rem;
    
    @media screen and (max-width: 1024px) {
        grid-template-columns: 1fr;
    }    
`;

export const Column = styled.section`
    display: grid;
    grid-template-columns: 1fr;
    grid-gap: 1.5rem;  
    grid-auto-rows: min-content;
`;

export const DangerousLink = styled.a`
    color: rgb(170, 0, 0);
    display: block;
    margin-bottom: .5rem;
    &:hover {
        color: rgba(170, 0, 0, 0.8);    
    }
`;
